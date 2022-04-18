<?php
 /* this is auto generated file */
 return [
    [
        'module_id' => 'note',
        'menu' => 'core.primaryMenu',
        'name' => 'Notes',
        'parent_name' => '',
        'label' => 'Notes',
        'ordering' => 3,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note',
            'subInfo' => 'Browse Notes you like to read.',
            'icon' => 'ico-newspaper-alt',
            'testid' => 'Notes'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'user.user.profileMenu',
        'name' => 'note',
        'parent_name' => '',
        'label' => 'Notes',
        'ordering' => 9,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note',
            'tab' => 'note',
            'testid' => 'note',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.profile_menu_settings.note_profile_menu'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'page.page.profileMenu',
        'name' => 'note',
        'parent_name' => '',
        'label' => 'Notes',
        'ordering' => 9,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note',
            'tab' => 'note',
            'testid' => 'note'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'group.group.profileMenu',
        'name' => 'note',
        'parent_name' => '',
        'label' => 'Notes',
        'ordering' => 9,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note',
            'tab' => 'note',
            'testid' => 'note'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'edit',
        'parent_name' => '',
        'label' => 'Edit',
        'ordering' => 1,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'edit',
            'icon' => 'ico-pencilline-o',
            'value' => 'closeMenu, editItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_edit'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'report',
        'parent_name' => '',
        'label' => 'Report',
        'ordering' => 2,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'report',
            'icon' => 'ico-warning-o',
            'value' => 'closeMenu, reportItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_report'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'publish',
        'parent_name' => '',
        'label' => 'Publish',
        'ordering' => 3,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'publish',
            'icon' => 'ico-check',
            'value' => 'closeMenu, publishNote',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_publish'
                ],
                [
                    'truthy',
                    'item.is_draft'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'approve',
        'parent_name' => '',
        'label' => 'Approve',
        'ordering' => 4,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'approve',
            'icon' => 'ico-check-circle-o',
            'value' => 'closeMenu, approveItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_pending'
                ],
                [
                    'truthy',
                    'item.extra.can_approve'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'sponsor_in_feed',
        'parent_name' => '',
        'label' => 'Sponsor in Feed',
        'ordering' => 5,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'sponsorInFeed',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, sponsorItemInFeed',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor_in_feed'
                ],
                [
                    'falsy',
                    'item.is_sponsored_feed'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'remove_sponsor_in_feed',
        'parent_name' => '',
        'label' => 'Un sponsor in Feed',
        'ordering' => 6,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeSponsorInFeed',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, unsponsorItemInFeed',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor_in_feed'
                ],
                [
                    'truthy',
                    'item.is_sponsored_feed'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'sponsor',
        'parent_name' => '',
        'label' => 'Sponsor this item',
        'ordering' => 7,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'sponsor',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, sponsorItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor'
                ],
                [
                    'falsy',
                    'item.is_sponsor'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'remove_sponsor',
        'parent_name' => '',
        'label' => 'Unsponsor this item',
        'ordering' => 8,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeSponsor',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, unsponsorItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor'
                ],
                [
                    'truthy',
                    'item.is_sponsor'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'feature',
        'parent_name' => '',
        'label' => 'Feature',
        'ordering' => 9,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'feature',
            'icon' => 'ico-diamond',
            'value' => 'closeMenu, featureItem',
            'showWhen' => [
                'and',
                [
                    'falsy',
                    'item.is_featured'
                ],
                [
                    'truthy',
                    'item.extra.can_feature'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'remove_feature',
        'parent_name' => '',
        'label' => 'Un-Feature',
        'ordering' => 10,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeFeature',
            'icon' => 'ico-diamond',
            'value' => 'closeMenu, unfeatureItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_featured'
                ],
                [
                    'truthy',
                    'item.extra.can_feature'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'save',
        'parent_name' => '',
        'label' => 'Save',
        'ordering' => 11,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'save',
            'icon' => 'ico-save-o',
            'value' => 'closeMenu, saveItem',
            'showWhen' => [
                'and',
                [
                    'falsy',
                    'item.is_saved'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'un-save',
        'parent_name' => '',
        'label' => 'Remove from Saved list',
        'ordering' => 12,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'un-save',
            'icon' => 'ico-list-del',
            'value' => 'closeMenu, undoSaveItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_saved'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.detailActionMenu',
        'name' => 'delete',
        'parent_name' => '',
        'label' => 'Delete',
        'ordering' => 13,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'delete',
            'icon' => 'ico-trash',
            'value' => 'closeMenu, deleteItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_delete'
                ]
            ],
            'className' => 'itemDelete'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'edit',
        'parent_name' => '',
        'label' => 'Edit',
        'ordering' => 1,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'edit',
            'icon' => 'ico-pencilline-o',
            'value' => 'closeMenu, editItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_edit'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'report',
        'parent_name' => '',
        'label' => 'Report',
        'ordering' => 2,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'report',
            'icon' => 'ico-warning-o',
            'value' => 'closeMenu, reportItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_report'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'publish',
        'parent_name' => '',
        'label' => 'Publish',
        'ordering' => 3,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'publish',
            'icon' => 'ico-check',
            'value' => 'closeMenu, publishNote',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_publish'
                ],
                [
                    'truthy',
                    'item.is_draft'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'approve',
        'parent_name' => '',
        'label' => 'Approve',
        'ordering' => 4,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'approve',
            'icon' => 'ico-check-circle-o',
            'value' => 'closeMenu, approveItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_pending'
                ],
                [
                    'truthy',
                    'item.extra.can_approve'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'sponsor_in_feed',
        'parent_name' => '',
        'label' => 'Sponsor in Feed',
        'ordering' => 5,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'sponsorInFeed',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, sponsorItemInFeed',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor_in_feed'
                ],
                [
                    'falsy',
                    'item.is_sponsored_feed'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'remove_sponsor_in_feed',
        'parent_name' => '',
        'label' => 'Un sponsor in Feed',
        'ordering' => 6,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeSponsorInFeed',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, unsponsorItemInFeed',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor_in_feed'
                ],
                [
                    'truthy',
                    'item.is_sponsored_feed'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'sponsor',
        'parent_name' => '',
        'label' => 'Sponsor this item',
        'ordering' => 7,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'sponsor',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, sponsorItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor'
                ],
                [
                    'falsy',
                    'item.is_sponsor'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'remove_sponsor',
        'parent_name' => '',
        'label' => 'Unsponsor this item',
        'ordering' => 8,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeSponsor',
            'icon' => 'ico-sponsor',
            'value' => 'closeMenu, unsponsorItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_sponsor'
                ],
                [
                    'truthy',
                    'item.is_sponsor'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'feature',
        'parent_name' => '',
        'label' => 'Feature',
        'ordering' => 9,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'feature',
            'icon' => 'ico-diamond',
            'value' => 'closeMenu, featureItem',
            'showWhen' => [
                'and',
                [
                    'falsy',
                    'item.is_featured'
                ],
                [
                    'truthy',
                    'item.extra.can_feature'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'remove_feature',
        'parent_name' => '',
        'label' => 'Un-Feature',
        'ordering' => 10,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'removeFeature',
            'icon' => 'ico-diamond',
            'value' => 'closeMenu, unfeatureItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_featured'
                ],
                [
                    'truthy',
                    'item.extra.can_feature'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'save',
        'parent_name' => '',
        'label' => 'Save',
        'ordering' => 11,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'save',
            'icon' => 'ico-save-o',
            'value' => 'closeMenu, saveItem',
            'showWhen' => [
                'and',
                [
                    'falsy',
                    'item.is_saved'
                ],
                [
                    'falsy',
                    'item.is_pending'
                ],
                [
                    'falsy',
                    'item.is_draft'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'un-save',
        'parent_name' => '',
        'label' => 'Remove from Saved list',
        'ordering' => 12,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'un-save',
            'icon' => 'ico-list-del',
            'value' => 'closeMenu, undoSaveItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.is_saved'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.note.itemActionMenu',
        'name' => 'delete',
        'parent_name' => '',
        'label' => 'Delete',
        'ordering' => 13,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'testid' => 'delete',
            'icon' => 'ico-trash',
            'value' => 'closeMenu, deleteItem',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'item.extra.can_delete'
                ]
            ],
            'className' => 'itemDelete'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'landing',
        'parent_name' => '',
        'label' => 'Home',
        'ordering' => 1,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note',
            'tab' => 'landing',
            'icon' => 'ico-newspaper-alt-o',
            'testid' => 'landing'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'all',
        'parent_name' => '',
        'label' => 'All Notes',
        'ordering' => 2,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note/all',
            'tab' => 'all',
            'icon' => 'ico-hashtag',
            'testid' => 'all'
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'my',
        'parent_name' => '',
        'label' => 'My Notes',
        'ordering' => 3,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note/my',
            'tab' => 'my',
            'icon' => 'ico-user-man-o',
            'testid' => 'my',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'acl.note.note.create'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'friends',
        'parent_name' => '',
        'label' => 'Friends\' Notes',
        'ordering' => 4,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note/friend',
            'tab' => 'friend',
            'icon' => 'ico-user1-two-o',
            'testid' => 'friends',
            'showWhen' => [
                'and',
                [
                    'falsy',
                    'setting.core.general.friends_only_community'
                ],
                [
                    'truthy',
                    'session.loggedIn'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'draft',
        'parent_name' => '',
        'label' => 'My Draft Notes',
        'ordering' => 5,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note/draft',
            'tab' => 'draft',
            'icon' => 'ico-pencilline-o',
            'testid' => 'draft',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'acl.note.note.create'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'pending',
        'parent_name' => '',
        'label' => 'Pending Notes',
        'ordering' => 6,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'to' => '/note/pending',
            'tab' => 'pending',
            'icon' => 'ico-clock-o',
            'testid' => 'pending',
            'showWhen' => [
                'or',
                [
                    'truthy',
                    'acl.note.note.approve'
                ],
                [
                    'truthy',
                    'acl.note.note.moderate'
                ]
            ]
        ]
    ],
    [
        'module_id' => 'note',
        'menu' => 'note.sidebarMenu',
        'name' => 'add',
        'parent_name' => '',
        'label' => 'note.phrase.create_new_note',
        'ordering' => 7,
        'is_deleted' => 0,
        'version' => 0,
        'is_active' => 1,
        'extra' => [
            'as' => 'sidebarButton',
            'icon' => 'ico-plus',
            'to' => '/note/add',
            'testid' => 'add',
            'showWhen' => [
                'and',
                [
                    'truthy',
                    'acl.note.note.create'
                ]
            ],
            'buttonProps' => [
                'fullWidth' => true,
                'color' => 'primary',
                'variant' => 'contained'
            ]
        ]
    ]
];
