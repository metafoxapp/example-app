<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Auth\AuthenticationException;
use MetaFox\Platform\MetaFoxForm;
use MetaFox\Platform\MetaFoxPrivacy;
use MetaFox\Platform\Support\Form\AbstractForm;
use MetaFox\Platform\Support\Form\Field\Attachment;
use MetaFox\Platform\Support\Form\Field\CancelButton;
use MetaFox\Platform\Support\Form\Field\CaptchaField;
use MetaFox\Platform\Support\Form\Field\CategoryField;
use MetaFox\Platform\Support\Form\Field\Hidden;
use MetaFox\Platform\Support\Form\Field\Privacy;
use MetaFox\Platform\Support\Form\Field\SinglePhotoField;
use MetaFox\Platform\Support\Form\Field\Submit;
use MetaFox\Platform\Support\Form\Field\TagsField;
use MetaFox\Platform\Support\Form\Field\Text;
use MetaFox\Platform\Support\Form\Field\TextArea;
use Company\Note\Http\Requests\v1\Note\StoreRequest;
use Company\Note\Models\Note as Model;
use Company\Note\Repositories\CategoryRepositoryInterface;
use MetaFox\User\Support\Facades\UserPrivacy;

/**
 * --------------------------------------------------------------------------
 * Form Configuration
 * --------------------------------------------------------------------------
 * stub: /packages/resources/edit_form.stub.
 */

/**
 * Class CreateNoteForm.
 * @property Model $resource
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreateNoteForm extends AbstractForm
{
    public bool $preserveKeys = true;

    /**
     * @throws AuthenticationException
     */
    protected function prepare(): void
    {
        $context = user();
        $privacy = UserPrivacy::getItemPrivacySetting($context->entityId(), 'note.item_privacy');
        if ($privacy === false) {
            $privacy = MetaFoxPrivacy::EVERYONE;
        }

        $this->config([
            'title'  => 'Add New Note',
            'action' => url_utility()->makeApiUrl('note'),
            'method' => MetaFoxForm::METHOD_POST,
            'value'  => [
                'module_id'   => 'note',
                'privacy'     => $privacy,
                'draft'       => 0,
                'tags'        => [],
                'owner_id'    => $this->resource->owner_id,
                'attachments' => [],
            ],
        ]);
    }

    protected function initialize(): void
    {
        $basic = $this->addBasic();
        $textMaxLength = 255;
        $isDraft = $this->resource->is_draft;

        $basic->addFields(
            new Text([
                'name'          => 'title',
                'required'      => true,
                'returnKeyType' => 'next',
                'margin'        => 'normal',
                'label'         => __p('core.phrase.title'),
                'placeholder'   => __p('note.phrase.fill_in_a_title_for_your_note'),
                'validation'    => [
                    'type'      => 'string',
                    'required'  => true,
                    'maxLength' => $textMaxLength,
                    'errors'    => [
                        'maxLength' => __p('validation.field_must_be_at_most_max_length_characters', [
                            'field'     => 'Title',
                            'maxLength' => $textMaxLength,
                        ]),
                    ],
                ],
            ]),
            new SinglePhotoField([
                'item_type'       => 'note',
                'thumbnail_sizes' => $this->resource->getSizes(),
                'preview_url'     => $this->resource->image,
            ]),
            new TextArea([
                'name'          => 'text',
                'required'      => true,
                'returnKeyType' => 'default',
                'label'         => __p('core.phrase.post'),
                'placeholder'   => __p('note.phrase.add_some_content_to_your_note'),
                'validation'    => [
                    'required' => true,
                ],
            ]),
            new Attachment([
                'item_type' => 'note',
            ]),
            new CategoryField([
                'name'       => 'categories',
                'multiple'   => true,
                'size'       => 'large',
                'repository' => CategoryRepositoryInterface::class,
            ]),
            new TagsField([
                'label'       => 'Topics',
                'placeholder' => 'Keywords',
            ]),
            new Privacy([
                'name'        => 'privacy',
                'description' => __p('note.phrase.control_who_can_see_this_note'),
            ]),
            new Hidden([
                'name' => 'module_id',
            ]),
            new Hidden([
                'name' => 'owner_id',
            ])
        );
        if (null == $this->resource->id && hasCaptcha('note.enable_captcha_challenge_when_adding_a_new_note')) {
            $basic->addField(new CaptchaField(['action_name' => StoreRequest::ACTION_CAPTCHA_NAME]));
        }

        $footer = $this->addFooter();

        $footer->addFields(
            new Submit([
                'label'     => $this->isEdit & !$isDraft ? __p('core.phrase.update') : __p('core.phrase.publish'),
                'flexWidth' => true,
            ]),
            new Submit([
                'name'     => 'draft',
                'color'    => 'primary',
                'variant'  => 'outlined',
                'label'    => $this->isEdit ? __p('core.phrase.update') : __p('core.phrase.save_as_draft'),
                'value'    => 1,
                'showWhen' => ['falsy', 'published'],
            ]),
            new CancelButton([
                'size' => 'medium',
            ]),
        );

        // force returnUrl as string
        $basic->addField(new Hidden(['name' => 'returnUrl']));
    }
}
