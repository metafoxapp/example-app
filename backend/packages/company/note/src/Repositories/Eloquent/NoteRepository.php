<?php

namespace Company\Note\Repositories\Eloquent;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use MetaFox\Platform\Contracts\HasFeature;
use MetaFox\Platform\Contracts\HasHashTag;
use MetaFox\Platform\Contracts\HasPrivacyMember;
use MetaFox\Platform\Contracts\ResourceText;
use MetaFox\Platform\Contracts\User;
use MetaFox\Platform\Facades\Settings;
use MetaFox\Platform\MetaFoxPrivacy;
use MetaFox\Platform\Repositories\AbstractRepository;
use MetaFox\Platform\Support\Browse\Browse;
use MetaFox\Platform\Support\Browse\Scopes\CategoryScope;
use MetaFox\Platform\Support\Browse\Scopes\PrivacyScope;
use MetaFox\Platform\Support\Browse\Scopes\SearchScope;
use MetaFox\Platform\Support\Browse\Scopes\SortScope;
use MetaFox\Platform\Support\Browse\Scopes\TagScope;
use MetaFox\Platform\Support\Browse\Scopes\WhenScope;
use MetaFox\Platform\Support\Repository\HasApprove;
use MetaFox\Platform\Support\Repository\HasFeatured;
use MetaFox\Platform\Support\Repository\HasSponsor;
use MetaFox\Platform\Support\Repository\HasSponsorInFeed;
use Company\Note\Models\Note;
use Company\Note\Policies\NotePolicy;
use Company\Note\Repositories\NoteRepositoryInterface;
use Company\Note\Support\Browse\Scopes\Note\ViewScope;
use Company\Note\Support\CacheManager;
use MetaFox\Core\Repositories\AttachmentRepositoryInterface;
use MetaFox\Hashtag\Repositories\HashtagRepositoryInterface;

/**
 * Class NoteRepository.
 * @property Note $model
 * @method Note getModel()
 * @method Note find($id, $columns = ['*'])
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class NoteRepository extends AbstractRepository implements NoteRepositoryInterface
{
    use HasSponsor;
    use HasFeatured;
    use HasApprove;
    use HasSponsorInFeed;

    public function model(): string
    {
        return Note::class;
    }

    public function createNote(User $context, User $owner, array $attributes): Note
    {
        policy_authorize(NotePolicy::class, 'create', $context, $owner);

        $waitingTime = $this->getWaitingTime($context, $owner);

        if ($waitingTime > 0) {
            abort(
                Response::HTTP_FORBIDDEN,
                __p('note.phrase.you_are_posting_a_little_too_soon_dot_try_again_in_seconds', [
                    'quantity' => $waitingTime,
                ])
            );
        }

        $attributes = array_merge($attributes, [
            'user_id'    => $context->entityId(),
            'user_type'  => $context->entityType(),
            'owner_id'   => $owner->entityId(),
            'owner_type' => $owner->entityType(),
            'module_id'  => Note::ENTITY_TYPE,
        ]);

        $attributes['title'] = $this->cleanTitle($attributes['title']);

        if ($attributes['temp_file'] > 0) {
            $tempFile = upload()->getTempFile($attributes['temp_file']);
            $attributes['image_path'] = $tempFile->path;
            $attributes['server_id'] = $tempFile->server_id;

            // Delete temp file after done
            upload()->deleteTempFile($attributes['temp_file']);
        }

        //only apply auto approve when $context == $owner
        if ($context->entityId() == $owner->entityId()) {
            if (!$context->hasPermissionTo('note.auto_approved')) {
                $attributes['is_approved'] = 0;
            }
        }

        $note = new Note($attributes);

        if ($attributes['privacy'] == MetaFoxPrivacy::CUSTOM) {
            $note->setPrivacyListAttribute($attributes['list']);
        }

        $note->save();

        $currentTags = [];
        if ($note instanceof HasHashTag) {
            $currentTags = $note->tagData()->get(['text'])->pluck('text')->toArray();
        }

        if (!empty($attributes['tags'])) {
            $attributes['tags'] = array_merge($currentTags, $attributes['tags']);
            $newHashTag = parse_input()->extractHashtag($attributes['tags'], true);
            app('events')->dispatch('hashtag.create_hashtag', [$context, $note, $newHashTag, true], true);
        }

        if (!empty($attributes['attachments'])) {
            resolve(AttachmentRepositoryInterface::class)->updateItemId($attributes['attachments'], $note);
        }

        $note->refresh();

        return $note;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function updateNote(User $context, int $id, array $attributes): Note
    {
        $note = $this->find($id);

        policy_authorize(NotePolicy::class, 'update', $context, $note);

        if (isset($attributes['title'])) {
            $attributes['title'] = $this->cleanTitle($attributes['title']);
        }

        if ($attributes['remove_image']) {
            if (null != $note->getThumbnail()) {
                deleteImageWithSize(
                    (string) $note->getThumbnail(),
                    $note->getThumbnailServerId(),
                    $note->getSizes(),
                    false
                );
                $attributes['image_path'] = null;
                $attributes['server_id'] = 0;
            }
        }

        if ($attributes['temp_file'] > 0) {
            $tempFile = upload()->getTempFile($attributes['temp_file']);
            $attributes['image_path'] = $tempFile->path;
            $attributes['server_id'] = $tempFile->server_id;

            // Delete temp file after done
            upload()->deleteTempFile($attributes['temp_file']);
        }

        $note->fill($attributes);

        if (isset($attributes['privacy']) && $attributes['privacy'] == MetaFoxPrivacy::CUSTOM) {
            $note->setPrivacyListAttribute($attributes['list']);
        }

        $note->save();

        $currentTags = [];
        if ($note->noteText instanceof ResourceText) {
            $currentTags = parse_output()->getHashtags($note->noteText->text_parsed);
        }

        if (array_key_exists('tags', $attributes)) {
            $currentTags = array_merge($currentTags, $attributes['tags']);
        }

        $newHashTag = parse_input()->extractHashtag($currentTags, true);
        app('events')->dispatch('hashtag.create_hashtag', [$context, $note, $newHashTag, true], true);

        if (0 == count($currentTags)) {
            $note->tagData()->sync([]);
        }

        if (isset($attributes['attachments'])) {
            resolve(AttachmentRepositoryInterface::class)->updateItemId($attributes['attachments'], $note);
        }

        $note->refresh();

        return $note;
    }

    public function deleteNote(User $user, $id): int
    {
        $resource = $this->find($id);

        policy_authorize(NotePolicy::class, 'delete', $user, $resource);

        return $this->delete($id);
    }

    public function viewNotes(User $context, User $owner, array $attributes): Paginator
    {
        policy_authorize(NotePolicy::class, 'viewAny', $context, $owner);
        $limit = $attributes['limit'];

        $view = $attributes['view'];
        if ($view == Browse::VIEW_FEATURE) {
            return $this->findFeature();
        }

        if ($view == Browse::VIEW_SPONSOR) {
            return $this->findSponsor();
        }

        if (Browse::VIEW_PENDING == $view) {
            if ($owner->entityId() != $context->entityId()) {
                if ($context->hasPermissionTo('note.approve') == false) {
                    throw new AuthorizationException(__p('core.validation.this_action_is_unauthorized'), 403);
                }
            }
        }

        $query = $this->buildQueryViewNotes($context, $owner, $attributes);
        $relations = ['noteText', 'user', 'userEntity', 'activeCategories'];

        /** @var \Illuminate\Pagination\Paginator $noteData */
        $noteData = $query
            ->with($relations)
            ->simplePaginate($limit, ['notes.*']);

        //Load sponsor on first page only
        if ($this->isNoSponsorView($view) || 1 < $noteData->currentPage()) {
            return $noteData;
        }

        $userId = $context->entityId();

        $cacheKey = sprintf(CacheManager::SPONSOR_ITEM_CACHE, $userId);
        $cacheTime = CacheManager::SPONSOR_ITEM_CACHE_TIME;

        return $this->transformPaginatorWithSponsor($noteData, $cacheKey, $cacheTime, 'id', $relations);
    }

    /**
     * @param User                 $context
     * @param User                 $owner
     * @param array<string, mixed> $attributes
     *
     * @return Builder
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function buildQueryViewNotes(User $context, User $owner, array $attributes): Builder
    {
        $sort = $attributes['sort'];
        $sortType = $attributes['sort_type'];
        $when = $attributes['when'] ?? '';
        $view = $attributes['view'] ?? '';
        $search = $attributes['q'] ?? '';
        $searchTag = $attributes['tag'] ?? '';
        $categoryId = $attributes['category_id'];
        $profileId = $attributes['user_id']; //$profileId == $owner->entityId() if has param user_id

        if ($profileId == $context->entityId() && $view != Browse::VIEW_PENDING) {
            $view = Browse::VIEW_MY;
        }

        // Scopes.
        $privacyScope = new PrivacyScope();
        $privacyScope->setUserId($context->entityId());

        $sortScope = new SortScope();
        $sortScope->setSort($sort)->setSortType($sortType);

        $whenScope = new WhenScope();
        $whenScope->setWhen($when);

        $viewScope = new ViewScope();
        $viewScope->setUserContext($context)->setView($view)->setProfileId($profileId);

        $query = $this->getModel()->newQuery();

        if ($search != '') {
            $searchScope = new SearchScope();
            $searchScope->setSearchText($search)->setFields(['title']);
            $query = $query->addScope($searchScope);
        }

        if ($searchTag != '') {
            $query = $query->addScope(new TagScope($searchTag));
        }

        if ($profileId > 0) {
            $query->where('notes.is_draft', '!=', Note::IS_DRAFT);
        }

        if ($owner->entityId() != $context->entityId()) {
            $privacyScope->setOwnerId($owner->entityId());

            $viewScope->setIsViewOwner(true);

            if (!policy_check(NotePolicy::class, 'approve', $context, resolve(Note::class))) {
                $query->where('notes.is_approved', '=', Note::IS_APPROVED);
            }
        }

        if ($categoryId > 0) {
            if (!is_array($categoryId)) {
                $categoryId = [$categoryId];
            }

            $categoryScope = new CategoryScope();
            $categoryScope->setCategories($categoryId);
            $query = $query->addScope($categoryScope);
        }

        $query = $this->applyDisplayNoteSetting($query, $owner, $view);

        return $query
            ->addScope($privacyScope)
            ->addScope($sortScope)
            ->addScope($whenScope)
            ->addScope($viewScope);
    }

    /**
     * @param Builder $query
     * @param User    $owner
     * @param string  $view
     *
     * @return Builder
     */
    private function applyDisplayNoteSetting(Builder $query, User $owner, string $view): Builder
    {
        if (!$owner instanceof HasPrivacyMember) {
            $condition = [];
            $checkViewAll = in_array($view, [Browse::VIEW_ALL, Browse::VIEW_ALL_DEFAULT, Browse::VIEW_LATEST]);

            if (!app_active('metafox/page') || ($checkViewAll && !Settings::get(
                'note.display_note_created_in_page',
                true
            ))) {
                $condition[] = ['notes.owner_type', '<>', 'page'];
            }

            if (!app_active('metafox/group') || ($checkViewAll && !Settings::get(
                'note.display_note_created_in_group',
                true
            ))) {
                $condition[] = ['notes.owner_type', '<>', 'group'];
            }

            $query->where($condition);
        }

        return $query;
    }

    public function viewNote(User $context, int $id): Note
    {
        $note = $this
            ->with(['noteText', 'user', 'userEntity', 'categories', 'activeCategories', 'attachments'])
            ->find($id);

        policy_authorize(NotePolicy::class, 'view', $context, $note);

        $note->incrementTotalView();
        $note->refresh();

        return $note;
    }

    public function findFeature(int $limit = 4): Paginator
    {
        return $this->getModel()->newQuery()
            ->where('is_featured', Note::IS_FEATURED)
            ->where('is_approved', Note::IS_APPROVED)
            ->where('is_draft', '<>', Note::IS_DRAFT)
            ->orderByDesc(HasFeature::FEATURED_AT_COLUMN)
            ->simplePaginate($limit);
    }

    public function findSponsor(int $limit = 4): Paginator
    {
        return $this->getModel()->newQuery()
            ->where('is_sponsor', Note::IS_SPONSOR)
            ->where('is_approved', Note::IS_APPROVED)
            ->where('is_draft', '<>', Note::IS_DRAFT)
            ->simplePaginate($limit);
    }

    public function publish(User $user, int $id): bool
    {
        $note = $this->find($id);

        policy_authorize(NotePolicy::class, 'publish', $user, $note);

        return $note->update(['is_draft' => 0]);
    }

    public function getWaitingTime(User $context, User $owner): int
    {
        /** @var \MetaFox\User\Models\User $context */
        $limit = (int) $context->getPermissionValue('note.flood_control');
        if ($limit <= 0) {
            return 0;
        }

        $lastNote = $this->getModel()->newModelInstance()->newQuery()
            ->where('user_id', $context->entityId())
            ->where('owner_id', $owner->entityId())
            ->orderByDesc('created_at')
            ->first();

        if (null === $lastNote) {
            return 0;
        }

        $waitEnd = $lastNote->created_at->addMinutes($limit);
        if (Carbon::now()->greaterThan($waitEnd)) {
            return 0;
        }

        return (int) Carbon::now()->diffInSeconds($waitEnd);
    }
}
