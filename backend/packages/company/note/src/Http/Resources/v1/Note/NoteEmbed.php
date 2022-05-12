<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Company\Note\Models\Note;
use MetaFox\Hashtag\Traits\HasHashtagTextTrait;
use MetaFox\User\Http\Resources\v1\UserEntity\UserEntityDetail;

/**
 * Class NoteEmbed.
 * @property Note $resource
 */
class NoteEmbed extends NoteItem
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $shortDescription = '';
        if ($this->resource->noteText) {
            $shortDescription = parse_output()->getDescription($this->resource->noteText->text_parsed);
        }

        return [
            'id'            => $this->resource->entityId(),
            'module_name'   => $this->resource->entityType(),
            'resource_name' => $this->resource->entityType(),
            'title'         => $this->resource->title,
            'description'   => $shortDescription,
            'image'         => $this->resource->images,
            'user'          => new UserEntityDetail($this->resource->userEntity),
            'privacy'       => $this->resource->privacy,
            'is_featured'   => $this->resource->is_featured,
            'link'          => $this->resource->toLink(),
            'url'           => $this->resource->toUrl(),
            'statistic'     => $this->getStatistic(),
        ];
    }
}
