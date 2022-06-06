<?php

namespace Company\Note\Http\Controllers\Api\v1;

use Company\Note\Http\Requests\v1\Note\IndexRequest;
use Company\Note\Http\Requests\v1\Note\StoreFormRequest;
use Company\Note\Http\Requests\v1\Note\StoreRequest;
use Company\Note\Http\Requests\v1\Note\UpdateRequest;
use Company\Note\Http\Resources\v1\Note\NoteDetail;
use Company\Note\Http\Resources\v1\Note\NoteItemCollection;
use Company\Note\Http\Resources\v1\Note\SearchNoteForm as SearchForm;
use Company\Note\Http\Resources\v1\Note\StoreNoteForm;
use Company\Note\Http\Resources\v1\Note\UpdateNoteForm;
use Company\Note\Models\Note;
use Company\Note\Policies\NotePolicy;
use Company\Note\Repositories\NoteRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use MetaFox\Platform\Http\Controllers\Api\ApiController;
use MetaFox\Platform\Http\Requests\v1\FeatureRequest;
use MetaFox\Platform\Http\Requests\v1\SponsorInFeedRequest;
use MetaFox\Platform\Http\Requests\v1\SponsorRequest;
use MetaFox\Platform\Support\Form\AbstractForm;
use MetaFox\User\Support\Facades\UserEntity;
use MetaFox\User\Support\Facades\UserPrivacy;

/**
 * Class NoteController.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NoteController extends ApiController
{
    public NoteRepositoryInterface $repository;

    public function __construct(NoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $params = $request->validated();
        $context = user();
        $owner = $context;
        if ($params['user_id'] > 0) {
            $owner = UserEntity::getById($params['user_id'])->detail;

            if (policy_check(NotePolicy::class, 'viewOnProfilePage', $context, $owner) == false) {
                return $this->success([]);
            }

            if (UserPrivacy::hasAccess($context, $owner, 'note.profile_menu') == false) {
                return $this->success([]);
            }
        }

        $data = $this->repository->viewNotes($context, $owner, $request->validated());

        return $this->success(new NoteItemCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     * @throws AuthenticationException | AuthorizationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $context = $owner = user();
        $params = $request->validated();

        if ($params['owner_id'] > 0) {
            if ($context->entityId() != $params['owner_id']) {
                $owner = UserEntity::getById($params['owner_id'])->detail;
            }
        }
        $message = __p('note::phrase.note_published_successfully');

        if ($params['is_draft']) {
            $message = __p('note::phrase.already_saved_note_as_draft');
        }

        $note = $this->repository->createNote($context, $owner, $params);

        return $this->success(new NoteDetail($note), [], $message);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return NoteDetail
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function show(int $id): NoteDetail
    {
        $note = $this->repository->viewNote(user(), $id);

        return new NoteDetail($note);
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthorizationException|AuthenticationException
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $note = $this->repository->updateNote(user(), $id, $params);
        $response = new NoteDetail($note);
        $message = __p('core.phrase.already_saved_changes');

        $isPublished = true;
        if (isset($params['published'])) {
            $isPublished = $params['published'];
        }

        if (!$isPublished) {
            if (!$params['is_draft']) {
                $message = __p('note::phrase.note_published_successfully');
            }
        }

        return $this->success($response, [], $message);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function destroy(int $id): JsonResponse
    {
        $context = user();
        $this->repository->deleteNote($context, $id);

        $message = __p('note::phrase.note_deleted_successfully');

        return $this->success([
            'id' => $id,
        ], [], $message);
    }

    /**
     * @param SponsorRequest $request
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException|AuthorizationException
     */
    public function sponsor(SponsorRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $sponsor = $params['sponsor'];
        $this->repository->sponsor(user(), $id, $sponsor);

        $isSponsor = (bool)$sponsor;

        $message = $isSponsor ? 'core.phrase.resource_sponsored_successfully' : 'core.phrase.resource_unsponsored_successfully';
        $message = __p($message, ['resource_name' => __p('note::phrase.note')]);

        return $this->success([
            'id' => $id,
            'is_sponsor' => $isSponsor,
        ], [], $message);
    }

    /**
     * @param FeatureRequest $request
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function feature(FeatureRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $feature = $params['feature'];
        $this->repository->feature(user(), $id, $feature);

        $message = __p('note::phrase.note_featured_successfully');
        if (!$feature) {
            $message = __p('note::phrase.note_unfeatured_successfully');
        }

        return $this->success([
            'id' => $id,
            'is_featured' => (int)$feature,
        ], [], $message);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function approve(int $id): JsonResponse
    {
        $this->repository->approve(user(), $id);

        // @todo recheck response.
        return $this->success([
            'id' => $id,
            'is_pending' => 0,
        ], [], __p('note::phrase.note_has_been_approved'));
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function publish(int $id): JsonResponse
    {
        $this->repository->publish(user(), $id);

        return $this->success([
            'id' => $id,
            'is_draft' => 0,
        ], [], __p('note::phrase.note_published_successfully'));
    }

    /**
     * @param StoreFormRequest $request
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function getStoreForm(StoreFormRequest $request): JsonResponse
    {
        $note = new Note();
        $context = user();

        $data = $request->validated();
        $note->owner_id = $data['owner_id'];
        policy_authorize(NotePolicy::class, 'create', $context);

        return $this->success(new StoreNoteForm($note), [], '');
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function getUpdateForm(int $id): JsonResponse
    {
        $context = user();

        $note = $this->repository->find($id);
        policy_authorize(NotePolicy::class, 'update', $context, $note);

        return $this->success(new UpdateNoteForm($note), [], '');
    }

    /**
     * @param SponsorInFeedRequest $request
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function sponsorInFeed(SponsorInFeedRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $sponsor = $params['sponsor'];
        $this->repository->sponsorInFeed(user(), $id, $sponsor);

        $isSponsor = (bool)$sponsor;

        $message = $isSponsor ? 'core.phrase.resource_sponsored_in_feed_successfully' : 'core.phrase.resource_unsponsored_in_feed_successfully';
        $message = __p($message, ['resource_name' => __p('note::phrase.note')]);

        return $this->success([
            'id' => $id,
            'is_sponsor' => $isSponsor,
        ], [], $message);
    }

    /**
     * @return AbstractForm
     * @todo Need working with policy + repository later
     */
    public function searchForm(): AbstractForm
    {
        return new SearchForm(null);
    }
}
