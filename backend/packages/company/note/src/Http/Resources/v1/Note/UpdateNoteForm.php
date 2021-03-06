<?php

namespace Company\Note\Http\Resources\v1\Note;

use MetaFox\Platform\MetaFoxForm;
use MetaFox\Platform\MetaFoxPrivacy;
use MetaFox\Platform\Support\Facades\PrivacyPolicy;

/**
 * --------------------------------------------------------------------------
 * Form Configuration
 * --------------------------------------------------------------------------
 * stub: /packages/resources/edit_form.stub.
 */

/**
 * Class UpdateNoteForm.
 */
class UpdateNoteForm extends StoreNoteForm
{
    /**
     * @var bool
     */
    protected $isEdit = true;

    protected function prepare(): void
    {
        $noteText = $this->resource->noteText;
        $privacy = $this->resource->privacy;

        if ($privacy == MetaFoxPrivacy::CUSTOM) {
            $lists = PrivacyPolicy::getPrivacyItem($this->resource);

            $listIds = [];
            if (!empty($lists)) {
                $listIds = array_column($lists, 'item_id');
            }

            $privacy = $listIds;
        }

        $this->config([
            'title'  => __p('note::phrase.edit_note'),
            'action' => url_utility()->makeApiUrl("note/{$this->resource->entityId()}"),
            'method' => MetaFoxForm::METHOD_PUT,
            'value'  => [
                'title'       => $this->resource->title,
                'module_id'   => $this->resource->module_id,
                'owner_id'    => $this->resource->owner_id,
                'text'        => $noteText != null ? parse_output()->parse($noteText->text_parsed) : '',
                'categories'  => $this->resource->categories->pluck('id')->toArray(),
                'privacy'     => $privacy,
                'published'   => !$this->resource->is_draft,
                'tags'        => $this->resource->tags,
                'attachments' => $this->resource->attachmentsForForm(),
                'draft'       => 0,
            ],
        ]);
    }
}
