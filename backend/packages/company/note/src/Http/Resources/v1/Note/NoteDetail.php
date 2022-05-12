<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MetaFox\Platform\Facades\PolicyGate;
use MetaFox\Platform\Traits\Helpers\IsFriendTrait;
use MetaFox\Platform\Traits\Helpers\IsLikedTrait;
use MetaFox\Platform\Traits\Http\Resources\HasExtra;
use MetaFox\Platform\Traits\Http\Resources\HasFeedParam;
use MetaFox\Platform\Traits\Http\Resources\HasStatistic;
use Company\Note\Http\Resources\v1\Category\CategoryItemCollection;
use Company\Note\Models\Note;
use MetaFox\Core\Http\Resources\v1\Attachment\AttachmentItemCollection;
use MetaFox\Hashtag\Traits\HasHashtagTextTrait;
use MetaFox\Hashtag\Http\Resources\v1\Hashtag\HashtagItemCollection;
use MetaFox\User\Http\Resources\v1\UserEntity\UserEntityDetail;

/**
 * Class NoteDetail.
 * @property Note $resource
 */
class NoteDetail extends JsonResource
{
    use HasExtra;
    use HasStatistic;
    use HasFeedParam;
    use IsLikedTrait;
    use IsFriendTrait;
    use HasHashtagTextTrait;

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

        $shortDescription = $text = '';
        if ($this->resource->noteText) {
            $shortDescription = parse_output()->getDescription($this->resource->noteText->text_parsed);
            $text = $this->getTransformContent($this->resource->noteText->text_parsed);
            $text = parse_output()->parse($text);
        }

        return [
            'id'                => $this->resource->entityId(),
            'module_name'       => $this->resource->entityType(),
            'resource_name'     => $this->resource->entityType(),
            'title'             => $this->resource->title,
            'description'       => $shortDescription,
            'module_id'         => $this->resource->ownerId() != $this->resource->userId() ? $this->resource->ownerType() : $this->resource->entityType(),
            'item_id'           => $this->resource->ownerId() != $this->resource->userId() ? $this->resource->ownerId() : 0,
            'is_approved'       => $isApproved,
            'is_sponsor'        => $this->resource->is_sponsor,
            'is_featured'       => $this->resource->is_featured,
            'is_liked'          => $this->isLike($context, $this->resource),
            'is_friend'         => $this->isFriend($context, $this->resource->user),
            'is_pending'        => $isPending,
            'is_draft'          => $isDraft,
            'is_saved'          => PolicyGate::check($this->resource->entityType(), 'isSavedItem', [$context, $this->resource]),
            'post_status'       => $this->resource->is_draft ? Note::STATUS_DRAFT : Note::STATUS_PUBLIC,
            'text'              => $text,
            'image'             => $this->resource->images,
            'statistic'         => $this->getStatistic(),
            'privacy'           => $this->resource->privacy,
            'user'              => new UserEntityDetail($this->resource->userEntity),
            'categories'        => (new CategoryItemCollection($this->resource->activeCategories)),
            'tags'              => $this->resource->tags,
            'attachments'       => new AttachmentItemCollection($this->resource->attachments),
            'is_sponsored_feed' => $this->resource->sponsor_in_feed,
            'creation_date'     => $this->resource->created_at,
            'modification_date' => $this->resource->updated_at,
            'link'              => $this->resource->toLink(),
            'url'               => $this->resource->toUrl(),
            'extra'             => $this->getExtra(),
            'feed_param'        => $this->getFeedParams(),
        ];
    }
}
