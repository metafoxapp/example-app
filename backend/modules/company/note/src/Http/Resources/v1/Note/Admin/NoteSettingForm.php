<?php

namespace Company\Note\Http\Resources\v1\Note\Admin;

use Illuminate\Support\Arr;
use MetaFox\Platform\Facades\Settings;
use MetaFox\Platform\PhpfoxForm;
use MetaFox\Platform\Support\Form\AbstractForm;
use MetaFox\Platform\Support\Form\Field\DividerField;
use MetaFox\Platform\Support\Form\Field\Submit;
use MetaFox\Platform\Support\Form\Field\SwitchField;
use Company\Note\Models\Note as Model;

/**
 * --------------------------------------------------------------------------
 * Form Configuration
 * --------------------------------------------------------------------------
 * stub: /modules/resources/edit_form.stub.
 */

/**
 * Class NoteSettingForm.
 * @property Model $resource
 */
class NoteSettingForm extends AbstractForm
{
    protected function prepare(): void
    {
        $module = 'note';
        $vars = [
            'display_note_created_in_page',
            'display_note_created_in_group',
            'note_allow_create_feed_when_add_new_item',
            'enable_captcha_challenge_when_adding_a_new_note',
        ];

        $value = [];

        foreach ($vars as $var) {
            Arr::set($value, $var, Settings::get($module . '.' . $var));
        }

        $this->config([
            'title'  => __('core.phrase.site_settings'),
            'action' => url_utility()->makeApiUrl('admincp/setting/' . $module),
            'method' => PhpfoxForm::METHOD_POST,
            'value'  => $value,
        ]);
    }

    protected function initialize(): void
    {
        $basic = $this->addBasic();
        $basic->addFields(
            new SwitchField([
                'name'        => 'note_allow_create_feed_when_add_new_item',
                'label'       => __p('note.phrase.note_allow_create_feed_when_add_new_item'),
                'description' => __p('note.phrase.note_allow_create_feed_when_add_new_item_description'),
            ]),
            new DividerField(),
        );

        if (app_active('metafox/page')) {
            $basic->addFields(
                new DividerField(),
                new SwitchField([
                    'name'        => 'display_note_created_in_page',
                    'label'       => __p('note.phrase.display_note_created_in_page'),
                    'description' => __p('note.phrase.display_note_created_in_page_description'),
                ]),
            );
        }

        if (app_active('metafox/group')) {
            $basic->addFields(
                new DividerField(),
                new SwitchField([
                    'name'        => 'display_note_created_in_group',
                    'label'       => __p('note.phrase.display_note_created_in_group'),
                    'description' => __p('note.phrase.display_note_created_in_group_description'),
                ]),
            );
        }

        $basic->addFields(
            new DividerField(),
            new SwitchField([
                'name'        => 'enable_captcha_challenge_when_adding_a_new_note',
                'label'       => __p('note.phrase.enable_captcha_challenge_when_adding_a_new_note'),
                'description' => __p('note.phrase.enable_captcha_challenge_when_adding_a_new_note'),
            ]),
        );

        $footer = $this->addFooter();
        $footer->addFields(
            new Submit([
                'label' => __p('core.phrase.save_changes'),
            ]),
        );
    }
}
