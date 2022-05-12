<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use MetaFox\Platform\Contracts\Policy\SaveItemPolicyInterface;
use MetaFox\Platform\Facades\PolicyGate;
use MetaFox\Platform\Traits\Helpers\IsFriendTrait;
use MetaFox\Platform\Traits\Helpers\IsLikedTrait;
use MetaFox\Platform\Traits\Http\Resources\HasExtra;
use MetaFox\Platform\Traits\Http\Resources\HasStatistic;
use Company\Note\Http\Resources\v1\Category\CategoryItemCollection;
use Company\Note\Models\Note;
use MetaFox\Hashtag\Http\Resources\v1\Hashtag\HashtagItemCollection;
use MetaFox\User\Http\Resources\v1\UserEntity\UserEntityDetail;

/**
 * Class NoteItem.
 * @property Note $resource
 */
class NoteItem extends JsonResource
{
    use HasStatistic;
    use HasExtra;
    use IsLikedTrait;
    use IsFriendTrait;

    /**
     * @return array<string, mixed>
     */
    public function getStatistic(): array
    {
        return [
            'total_like'       => $this->resource->total_like,
            'total_view'       => $this->resource->total_view,
            'total_share'      => $this->resource->total_share,
            'total_comment'    => $this->resource->total_comment,
            'total_attachment' => $this->resource->total_attachment,
        ];
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws AuthenticationException
     */
    public function toArray($request): array
    {
        $context = user();
        $isDraft = $this->resource->is_draft;
        $isApproved = $this->resource->is_approved;
        $isPending = false;
        if (!$isDraft) {
            if (!$isApproved) {
                $isPending = true;
            }
        }

        $shortDescription = '';
        if ($this->resource->noteText) {
            $shortDescription = parse_output()->getDescription($this->resource->noteText->text_parsed);
        }

        return [
            'id'                => $this->resource->entityId(),
            'module_name'       => $this->resource->entityType(),
            'resource_name'     => $this->resource->entityType(),
            'title'             => $this->resource->title,
            'description'       => $shortDescription,
            'module_id'         => $this->resource->ownerId() != $this->resource->userId() ? $this->resource->ownerType() : $this->resource->entityType(),
            'item_id'           => $this->resource->ownerId() != $this->resource->userId() ? $this->resource->ownerId() : 0,
            'privacy'           => $this->resource->privacy,
            'image'             => $this->resource->images,
            'is_approved'       => $isApproved,
            'is_sponsor'        => $this->resource->is_sponsor,
            'is_sponsored_feed' => $this->resource->sponsor_in_feed,
            'is_featured'       => $this->resource->is_featured,
            'is_liked'          => $this->isLike($context, $this->resource),
            'is_friend'         => $this->isFriend($context, $this->resource->user),
            'is_pending'        => $isPending,
            'is_draft'          => $isDraft,
            'is_saved'          => PolicyGate::check($this->resource->entityType(), 'isSavedItem', [$context, $this->resource]),
            'post_status'       => $this->resource->is_draft ? Note::STATUS_DRAFT : Note::STATUS_PUBLIC,
            'tags'              => $this->resource->tags,
            'attachments'       => [], //Todo: add attachments
            'statistic'         => $this->getStatistic(),
            'user'              => new UserEntityDetail($this->resource->userEntity),
            'categories'        => new CategoryItemCollection($this->resource->activeCategories),
            'link'              => $this->resource->toLink(),
            'url'               => $this->resource->toUrl(),
            'creation_date'     => $this->resource->created_at,
            'modification_date' => $this->resource->updated_at,
            'extra'             => $this->getExtra(),
        ];
    }
}
