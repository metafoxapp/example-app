<?php

namespace Company\Note\Policies;

use MetaFox\Platform\Contracts\Content;
use MetaFox\Platform\Contracts\HasApprove;
use MetaFox\Platform\Contracts\HasPublish;
use MetaFox\Platform\Contracts\Policy\ResourcePolicyInterface;
use MetaFox\Platform\Contracts\Policy\ViewOnProfilePagePolicyInterface;
use MetaFox\Platform\Contracts\User as User;
use MetaFox\Platform\Support\Facades\PrivacyPolicy;
use MetaFox\Platform\Traits\Policy\HasPolicyTrait;
use MetaFox\Platform\Traits\Policy\ViewOnProfilePagePolicyTrait;
use Modules\User\Support\Facades\UserPrivacy;

/**
 * @SuppressWarnings(PHPMD)
 */
class NotePolicy implements
    ResourcePolicyInterface,
    ViewOnProfilePagePolicyInterface
{
    use HasPolicyTrait;
    use ViewOnProfilePagePolicyTrait;

    public function viewAny(User $user, ?User $owner = null): bool
    {
        if (!$user->hasPermissionTo('note.view')) {
            return false;
        }

        if ($owner instanceof User) {
            if (!$this->viewOwner($user, $owner)) {
                return false;
            }
        }

        return true;
    }

    public function view(User $user, Content $resource): bool
    {
        if (!$user->hasPermissionTo('note.view')) {
            return false;
        }

        $owner = $resource->owner;

        if ($this->viewOwner($user, $owner) == false) {
            return false;
        }

        // Check can view on resource.
        if (PrivacyPolicy::checkPermission($user, $resource) == false) {
            return false;
        }

        // Check setting view on resource.
        if ($resource instanceof HasApprove) {
            if (HasApprove::IS_APPROVED != $resource->is_approved) {
                if (!$user->hasPermissionTo('note.approve')) {
                    return false;
                }
            }
        }

        if ($resource instanceof HasPublish) {
            if (HasPublish::IS_DRAFT == $resource->is_draft) {
                if ($resource->userId() != $user->entityId()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function viewOwner(User $user, User $owner): bool
    {
        // Check can view on owner.
        if (!PrivacyPolicy::checkPermissionOwner($user, $owner)) {
            return false;
        }

        if (UserPrivacy::hasAccess($user, $owner, 'note.view_browse_notes') == false) {
            return false;
        }

        return true;
    }

    public function create(User $user, ?User $owner = null): bool
    {
        if (!$user->hasPermissionTo('note.create')) {
            return false;
        }

        if ($owner instanceof User) {
            if ($owner->entityId() != $user->entityId()) {
                if ($owner->entityType() == 'user') {
                    return false;
                }

                // Check can view on owner.
                if (!PrivacyPolicy::checkPermissionOwner($user, $owner)) {
                    return false;
                }

                if (!UserPrivacy::hasAccess($user, $owner, 'note.share_notes')) {
                    return false;
                }
            }
        }

        return true;
    }

    public function update(User $user, ?Content $resource = null): bool
    {
        if ($user->hasPermissionTo('note.moderate')) {
            return true;
        }

        if ($resource instanceof Content) {
            if ($user->entityId() != $resource->userId()) {
                return false;
            }
        }

        return $user->hasPermissionTo('note.update');
    }

    public function delete(User $user, ?Content $resource = null): bool
    {
        if ($user->hasPermissionTo('note.moderate')) {
            return true;
        }

        return $this->deleteOwn($user, $resource);
    }

    public function deleteOwn(User $user, ?Content $resource = null): bool
    {
        if (!$user->hasPermissionTo('note.delete')) {
            return false;
        }

        if ($resource instanceof Content) {
            if ($user->entityId() != $resource->userId()) {
                return false;
            }
        }

        return true;
    }
}
