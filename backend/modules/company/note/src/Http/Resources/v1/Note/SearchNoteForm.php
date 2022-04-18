<?php

namespace Company\Note\Http\Resources\v1\Note;

use MetaFox\Platform\Support\Browse\Browse;
use MetaFox\Platform\Support\Form\AbstractForm;
use MetaFox\Platform\Support\Form\Field\Choice;
use MetaFox\Platform\Support\Form\Field\FilterCategoryField;
use MetaFox\Platform\Support\Form\Field\SearchBoxField;
use Company\Note\Models\Note as Model;

/**
 * @property Model $resource
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class SearchNoteForm extends AbstractForm
{
    protected function prepare(): void
    {
        $this->config([
            'action'           => '/note/search',
            'acceptPageParams' => ['q', 'sort', 'when', 'category_id', 'returnUrl'],
        ]);
    }

    protected function initialize(): void
    {
        $basic = $this->addBasic();

        $basic->addFields(
            new SearchBoxField(
                [
                    'name'        => 'q',
                    'placeholder' => 'Search Note',
                    'className'   => 'mb2',
                ]
            ),
            new Choice([
                'name'    => 'sort',
                'label'   => 'Sort',
                'margin'  => 'normal',
                'size'    => 'large',
                'options' => [
                    ['label' => 'Recent', 'value' => Browse::SORT_LATEST],
                    ['label' => 'Most Viewed', 'value' => Browse::SORT_MOST_VIEWED],
                    ['label' => 'Most Liked', 'value' => Browse::SORT_MOST_LIKED],
                    ['label' => 'Most Discussed', 'value' => Browse::SORT_MOST_DISCUSSED],
                ],
            ]),
            new Choice([
                'name'    => 'when',
                'label'   => 'When',
                'margin'  => 'normal',
                'size'    => 'large',
                'options' => [
                    ['label' => 'All', 'value' => Browse::WHEN_ALL],
                    ['label' => 'This Month', 'value' => Browse::WHEN_THIS_MONTH],
                    ['label' => 'This Week', 'value' => Browse::WHEN_THIS_WEEK],
                    ['label' => 'Today', 'value' => Browse::WHEN_TODAY],
                ],
            ]),
            new FilterCategoryField([
                'name'   => 'category_id',
                'label'  => 'Categories',
                'apiUrl' => '/note-category',
                'margin' => 'normal',
                'size'   => 'large',
            ])
        );
    }
}
