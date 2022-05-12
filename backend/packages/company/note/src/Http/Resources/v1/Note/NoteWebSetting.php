<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Http\Resources\v1\Note;

use MetaFox\Platform\Support\Resource\WebSetting;

/**
 *--------------------------------------------------------------------------
 * Note Web Resource Setting
 *--------------------------------------------------------------------------
 * stub: /packages/resources/resource_setting.stub
 * Add this class name to resources config gateway.
 */

/**
 * Class NoteWebSetting
 * Inject this class into property $resources.
 * @link \Company\Note\Http\Resources\v1\WebAppSetting::$resources;
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class NoteWebSetting extends WebSetting
{
    protected function initActions(): void
    {
        $this->addActions([
            'searchItem'         => [
                'pageUrl'     => '/note/search',
                'placeholder' => 'Search notes',
            ],
            'homePage'           => [
                'pageUrl' => '/note',
            ],
            'viewAll'            => [
                'pageUrl'  => '/note/all',
                'apiUrl'   => '/note',
                'apiRules' => [
                    'q'           => [
                        'truthy',
                        'q',
                    ],
                    'sort'        => [
                        'includes',
                        'sort',
                        [
                            'latest',
                            'most_viewed',
                            'most_liked',
                            'most_discussed',
                        ],
                    ],
                    'tag'         => [
                        'truthy',
                        'tag',
                    ],
                    'category_id' => [
                        'truthy',
                        'category_id',
                    ],
                    'when'        => [
                        'includes',
                        'when',
                        [
                            'this_month',
                            'this_week',
                            'today',
                        ],
                    ],
                    'view'        => [
                        'includes',
                        'view',
                        [
                            'my',
                            'friend',
                            'pending',
                            'spam',
                            'feature',
                            'sponsor',
                            'draft',
                        ],
                    ],
                ],
            ],
            'viewItem'           => [
                'pageUrl' => '/note/:id',
                'apiUrl'  => '/note/:id',
            ],
            'deleteItem'         => [
                'apiUrl'  => '/note/:id',
                'confirm' => [
                    'title'   => 'Confirm',
                    'message' => 'Are you sure you want to delete this item permanently?',
                ],
            ],
            'editItem'           => [
                'pageUrl' => '/note/edit/:id',
                'apiUrl'  => '/note/form/:id',
            ],
            'addItem'            => [
                'pageUrl' => '/note/add',
                'apiUrl'  => '/note/form',
            ],
            'publishNote'        => [
                'apiUrl'    => '/note/publish/:id',
                'apiMethod' => 'put',
                'confirm'   => [
                    'title'   => 'Confirm',
                    'message' => 'Are you sure you want to publish this draft note?',
                ],
            ],
            'approveItem' => [
                'apiUrl'    => '/note/approve/:id',
                'apiMethod' => 'patch',
            ],
            'sponsorItem'        => [
                'apiUrl' => '/note/sponsor/:id',
            ],
            'sponsorItemInFeed'  => [
                'apiUrl' => '/note/sponsor-in-feed/:id',
            ],
            'featureItem'        => [
                'apiUrl' => '/note/feature/:id',
            ],
        ]);
    }

    protected function initForms(): void
    {
        $this->addForms([
            'filter' => new SearchNoteForm(),
        ]);
    }
}
