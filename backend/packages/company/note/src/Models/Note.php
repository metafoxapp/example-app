<?php

namespace Company\Note\Models;

use Company\Note\Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MetaFox\Platform\Contracts\ActivityFeedSource;
use MetaFox\Platform\Contracts\Content;
use MetaFox\Platform\Contracts\HasFeature;
use MetaFox\Platform\Contracts\HasGlobalSearch;
use MetaFox\Platform\Contracts\HasHashTag;
use MetaFox\Platform\Contracts\HasPrivacy;
use MetaFox\Platform\Contracts\HasResourceCategory;
use MetaFox\Platform\Contracts\HasResourceStream;
use MetaFox\Platform\Contracts\HasSavedItem;
use MetaFox\Platform\Contracts\HasSponsor;
use MetaFox\Platform\Contracts\HasSponsorInFeed;
use MetaFox\Platform\Contracts\HasThumbnail;
use MetaFox\Platform\Contracts\HasTotalCommentWithReply;
use MetaFox\Platform\Contracts\HasTotalLike;
use MetaFox\Platform\Contracts\HasTotalShare;
use MetaFox\Platform\Contracts\HasTotalView;
use MetaFox\Platform\Support\Eloquent\Appends\AppendPrivacyListTrait;
use MetaFox\Platform\Support\Eloquent\Appends\Contracts\AppendPrivacyList;
use MetaFox\Platform\Support\FeedAction;
use MetaFox\Platform\Support\HasContent;
use MetaFox\Platform\Traits\Eloquent\Model\HasNestedAttributes;
use MetaFox\Platform\Traits\Eloquent\Model\HasOwnerMorph;
use MetaFox\Platform\Traits\Eloquent\Model\HasThumbnailTrait;
use MetaFox\Platform\Traits\Eloquent\Model\HasUserMorph;
use MetaFox\Core\Contracts\HasTotalAttachment;
use MetaFox\Core\Models\Tag;
use MetaFox\Core\Traits\HasTotalAttachmentTrait;

/**
 * Class Note.
 * @mixin Builder
 * @property int         $id
 * @property string      $title
 * @property string      $module_id
 * @property int         $privacy
 * @property bool        $is_draft
 * @property bool        $is_approved
 * @property bool        $is_featured
 * @property bool        $is_sponsor
 * @property bool        $sponsor_in_feed
 * @property int         $total_attachment
 * @property int         $total_view
 * @property int         $total_like
 * @property int         $total_comment
 * @property int         $total_share
 * @property ?string[]   $tags
 * @property int         $server_id
 * @property string|null $image_path
 * @property string      $created_at
 * @property string      $updated_at
 * @property ?NoteText   $noteText
 * @property Collection  $categories
 * @property Collection  $activeCategories
 * @method static NoteFactory factory(...$parameters)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @todo TriTV: he class Note has a coupling between objects value of 14. Consider to reduce the number of dependencies
 *       under 13. Please reduce this class.
 */
class Note extends Model implements
    Content,
    ActivityFeedSource,
    AppendPrivacyList,
    HasPrivacy,
    HasResourceStream,
    HasResourceCategory,
    HasFeature,
    HasHashTag,
    HasSponsor,
    HasSponsorInFeed,
    HasTotalLike,
    HasTotalShare,
    HasTotalCommentWithReply,
    HasTotalView,
    HasTotalAttachment,
    HasThumbnail,
    HasSavedItem,
    HasGlobalSearch
{
    use HasOwnerMorph;
    use HasUserMorph;
    use HasContent;
    use AppendPrivacyListTrait;
    use HasNestedAttributes;
    use HasFactory;
    use HasThumbnailTrait;
    use HasTotalAttachmentTrait;

    public const ENTITY_TYPE = 'note';

    public const STATUS_PUBLIC = 1;
    public const STATUS_DRAFT = 2;

    public const NO_IMAGE_PATH = 'assets/metafox/note/images/no_image.png';

    /**
     * @var string[]
     */
    protected $casts = [
        'is_approved'     => 'boolean',
        'is_sponsor'      => 'boolean',
        'sponsor_in_feed' => 'boolean',
        'is_featured'     => 'boolean',
        'is_draft'        => 'boolean',
        'tags'            => 'array',
    ];

    /**
     * @var array<string>|array<string, mixed>
     */
    public array $nestedAttributes = [
        'categories',
        'noteText' => ['text', 'text_parsed'],
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'module_id',
        'user_id',
        'user_type',
        'owner_id',
        'owner_type',
        'privacy',
        'is_draft',
        'is_approved',
        'is_featured',
        'is_sponsor',
        'sponsor_in_feed',
        'tags',
        'server_id',
        'image_path',
        'updated_at',
        'created_at',
        'total_like',
        'total_share',
        'total_comment',
        'total_reply',
        'total_attachment',
    ];

    /**
     * @return BelongsToMany
     */
    public function tagData(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'note_tag_data',
            'item_id',
            'tag_id'
        )->using(NoteTagData::class);
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'note_category_data',
            'item_id',
            'category_id'
        )->using(CategoryData::class);
    }

    /**
     * @return BelongsToMany
     */
    public function activeCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'note_category_data',
            'item_id',
            'category_id'
        )->where('is_active', Category::IS_ACTIVE)->using(CategoryData::class);
    }

    /**
     * @return HasOne
     */
    public function noteText(): HasOne
    {
        return $this->hasOne(NoteText::class, 'id', 'id');
    }

    /**
     * @return FeedAction
     */
    public function toActivityFeed(): ?FeedAction
    {
        if ($this->is_draft == 1) {
            return null;
        }

        return new FeedAction([
            'user_id'    => $this->userId(),
            'user_type'  => $this->userType(),
            'owner_id'   => $this->ownerId(),
            'owner_type' => $this->ownerType(),
            'item_id'    => $this->entityId(),
            'item_type'  => $this->entityType(),
            'type_id'    => $this->entityType(),
            'privacy'    => $this->privacy,
        ]);
    }

    protected static function newFactory(): NoteFactory
    {
        return NoteFactory::new();
    }

    public function privacyStreams(): HasMany
    {
        return $this->hasMany(PrivacyStream::class, 'item_id', 'id');
    }

    public function getThumbnail(): ?string
    {
        return $this->image_path;
    }

    public function getThumbnailServerId(): int
    {
        return $this->server_id;
    }

    public function toSavedItem(): array
    {
        return [
            'title'          => $this->title,
            'image'          => $this->images,
            'item_type_name' => __p("note.phrase.{$this->entityType()}_label_saved"),
            'total_photo'    => $this->getThumbnail() ? 1 : 0,
            'user'           => $this->userEntity,
            'link'           => $this->toLink(),
        ];
    }

    public function toSearchable(): ?array
    {
        $text = $this->noteText;

        return [
            'title' => $this->title,
            'text'  => $text ? $text->text_parsed : '',
        ];
    }

    public function toTitle(): string
    {
        return $this->title;
    }
}
