<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Listeners;

use MetaFox\Platform\Contracts\PackageSettingInterface;
use MetaFox\Platform\MetaFoxDataType;
use MetaFox\Platform\MetaFoxEvent;
use MetaFox\Platform\MetaFoxPrivacy;
use MetaFox\Platform\Support\BasePackageSettingListener;
use MetaFox\Platform\UserRole;
use Company\Note\GraphQL\Mutations\CreateNoteMutation;
use Company\Note\GraphQL\Mutations\CreateCategoryMutation;
use Company\Note\GraphQL\Mutations\DeleteNoteMutation;
use Company\Note\GraphQL\Mutations\DeleteCategoryMutation;
use Company\Note\GraphQL\Mutations\FeatureNoteMutation;
use Company\Note\GraphQL\Mutations\SponsorNoteMutation;
use Company\Note\GraphQL\Mutations\UpdateNoteMutation;
use Company\Note\GraphQL\Mutations\UpdateCategoryMutation;
use Company\Note\GraphQL\Queries\NoteCollectionQuery;
use Company\Note\GraphQL\Queries\NoteQuery;
use Company\Note\GraphQL\Queries\CategoryCollectionQuery;
use Company\Note\GraphQL\Queries\CategoryQuery;
use Company\Note\GraphQL\Types\NoteTextType;
use Company\Note\GraphQL\Types\NoteType;
use Company\Note\GraphQL\Types\CategoryType;
use Company\Note\Http\Resources\v1\WebSetting;
use Company\Note\Models\Note;
use Company\Note\Models\Category;
use Company\Note\Policies\NotePolicy;
use Company\Note\Policies\CategoryPolicy;

/**
 * Class PackageSettingListener.
 * @SuppressWarnings(PHPMD)
 */
class PackageSettingListener extends BasePackageSettingListener implements PackageSettingInterface
{
    public static function getPackageName(): string
    {
        return 'company/note';
    }

    public function getModuleName(): string
    {
        return 'note';
    }

    public function getActivityTypes(): array
    {
        return [
            [
                'type'            => Note::ENTITY_TYPE,
                'module_id'       => $this->getModuleName(),
                'entity_type'     => Note::ENTITY_TYPE,
                'is_active'       => true,
                'title'           => 'note.phrase.note_type',
                'description'     => 'note.phrase.added_a_note',
                'is_system'       => 0,
                'can_comment'     => true,
                'can_like'        => true,
                'can_share'       => true,
                'can_edit'        => false,
                'can_create_feed' => true,
                'can_put_stream'  => true,
            ],
        ];
    }

    public function getNotificationTypes(): array
    {
        return [
            [
                'type'       => 'note_notification',
                'module_id'  => 'note',
                'title'      => 'note.phrase.note_notification_type',
                'is_request' => 0,
                'is_system'  => 1,
                'can_edit'   => 1,
                'channels'   => ['database', 'mail'],
                'ordering'   => 3,
            ],
        ];
    }

    public function getPolicies(): array
    {
        return [
            Note::class     => NotePolicy::class,
            Category::class => CategoryPolicy::class,
        ];
    }

    public function getUserPermissions(): array
    {
        return [
            Note::ENTITY_TYPE     => [
                'viewAny'          => UserRole::LEVEL_GUEST,
                'view'             => UserRole::LEVEL_GUEST,
                'create'           => UserRole::LEVEL_REGISTERED,
                'update'           => UserRole::LEVEL_REGISTERED,
                'delete'           => UserRole::LEVEL_REGISTERED,
                'moderate'         => UserRole::LEVEL_STAFF,
                'feature'          => UserRole::LEVEL_REGISTERED,
                'approve'          => UserRole::LEVEL_STAFF,
                'sponsor'          => UserRole::LEVEL_REGISTERED,
                'publish'          => UserRole::LEVEL_REGISTERED,
                'save'             => UserRole::LEVEL_REGISTERED,
                'like'             => UserRole::LEVEL_REGISTERED,
                'share'            => UserRole::LEVEL_REGISTERED,
                'comment'          => UserRole::LEVEL_REGISTERED,
                'report'           => UserRole::LEVEL_REGISTERED,
                'sponsor_in_feed'  => UserRole::LEVEL_REGISTERED,
                'purchase_sponsor' => UserRole::LEVEL_REGISTERED,
                'auto_approved'    => UserRole::LEVEL_REGISTERED,
            ],
            Category::ENTITY_TYPE => [
                'view'   => UserRole::LEVEL_GUEST,
                'create' => UserRole::LEVEL_STAFF,
                'update' => UserRole::LEVEL_STAFF,
                'delete' => UserRole::LEVEL_STAFF,
            ],
        ];
    }

    public function getSiteSettings(): array
    {
        return [
            'display_note_created_in_page'                    => ['value' => true],
            'display_note_created_in_group'                   => ['value' => true],
            'note_allow_create_feed_when_add_new_item'        => ['value' => true],
            'no_image'                                        => [
                'value'   => '//s3.amazonaws.com/phpfox-chatplus/metafox/images/note/no_image.png',
                'is_auto' => 1, 'is_public' => 1, 'type' => 'string',
            ],
            'cover_no_image'                                  => [
                'value'   => '//s3.amazonaws.com/phpfox-chatplus/metafox/images/note/cover_no_image.png',
                'is_auto' => 1, 'is_public' => 1, 'type' => 'string',
            ],
            'enable_captcha_challenge_when_adding_a_new_note' => ['value' => false],
        ];
    }

    public function getEvents(): array
    {
        return [
            'models.notify.created'          => [
                ModelCreatedListener::class,
            ],
            'models.notify.updated'          => [
                ModelUpdatedListener::class,
            ],
            'models.notify.deleted'          => [
                ModelDeletedListener::class,
            ],
            'activity.update_feed_item_privacy' => [
                UpdateFeedItemPrivacyListener::class,
            ]
        ];
    }

    public function getUserPrivacy(): array
    {
        return [
            'note.share_notes'       => [
                'phrase' => 'note.phrase.user_privacy.who_can_share_notes',
            ],
            'note.view_browse_notes' => [
                'phrase' => 'note.phrase.user_privacy.who_can_view_notes',
            ],
        ];
    }

    public function getUserPrivacyResource(): array
    {
        return [
            'page'  => [
                'note.share_notes',
                'note.view_browse_notes',
            ],
            'group' => [
                'note.share_notes',
            ],
        ];
    }

    public function getDefaultPrivacy(): array
    {
        return [
            Note::ENTITY_TYPE => [
                'phrase'  => 'note.phrase.notes',
                'default' => MetaFoxPrivacy::EVERYONE,
            ],
        ];
    }

    public function getProfileMenu(): array
    {
        return [
            Note::ENTITY_TYPE => [
                'phrase'  => 'note.phrase.notes',
                'default' => MetaFoxPrivacy::EVERYONE,
            ],
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function getWebAppSettings(): ?array
    {
        return [
            'v1' => WebSetting::class,
        ];
    }

    public function getHttpResourceVersions(): array
    {
        return [
            Note::ENTITY_TYPE . '.item.v1'            => \Company\Note\Http\Resources\v1\Note\NoteItem::class,
            Note::ENTITY_TYPE . '.item_collection.v1' => \Company\Note\Http\Resources\v1\Note\NoteItemCollection::class,
            Note::ENTITY_TYPE . '.embed.v1'           => \Company\Note\Http\Resources\v1\Note\NoteEmbed::class,
            Note::ENTITY_TYPE . '.detail.v1'          => \Company\Note\Http\Resources\v1\Note\NoteDetail::class,
        ];
    }

    public function getUserValuePermissions(): array
    {
        return [
            Note::ENTITY_TYPE => [
                'activity_point.create' => [
                    'description' => 'specify_how_many_points_the_user_will_receive_when_adding_a_new_note',
                    'type'        => MetaFoxDataType::INTEGER,
                    'default'     => 1,
                    'roles'       => [
                        UserRole::ADMIN_USER  => 1,
                        UserRole::STAFF_USER  => 1,
                        UserRole::NORMAL_USER => 1,
                    ],
                ],
                'activity_point.delete' => [
                    'description' => 'specify_how_many_points_the_user_will_receive_when_deleting_a_note',
                    'type'        => MetaFoxDataType::INTEGER,
                    'default'     => -1,
                    'roles'       => [
                        UserRole::ADMIN_USER  => 0,
                        UserRole::STAFF_USER  => 0,
                        UserRole::NORMAL_USER => -1,
                    ],
                ],
                'flood_control'         => [
                    'description' => 'specify_how_many_minutes_should_a_user_wait_before_they_can_submit_another_note',
                    'type'        => MetaFoxDataType::INTEGER,
                    'default'     => 0,
                    'roles'       => [
                        UserRole::ADMIN_USER  => 0,
                        UserRole::STAFF_USER  => 0,
                        UserRole::NORMAL_USER => 0,
                    ],
                ],
            ],
        ];
    }

    public function getItemTypes(): array
    {
        return [
            Note::ENTITY_TYPE,
        ];
    }
}
