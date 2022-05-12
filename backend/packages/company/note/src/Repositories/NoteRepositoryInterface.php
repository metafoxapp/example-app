<?php

namespace Company\Note\Repositories;

use MetaFox\Platform\Contracts\Content;
use MetaFox\Platform\Support\Repository\Contracts\HasSponsorInFeed;
use MetaFox\Platform\Contracts\User;
use MetaFox\Platform\Support\Repository\Contracts\HasFeature;
use MetaFox\Platform\Support\Repository\Contracts\HasSponsor;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\Paginator;
use Company\Note\Models\Note;

/**
 * Interface NoteRepositoryInterface.
 * @method Note find($id, $columns = ['*'])
 * @method Note getModel()
 */
interface NoteRepositoryInterface extends HasSponsor, HasFeature, HasSponsorInFeed
{
    /**
     * @param User                 $context
     * @param User                 $owner
     * @param array<string, mixed> $attributes
     *
     * @return Paginator
     * @throws AuthorizationException
     */
    public function viewNotes(User $context, User $owner, array $attributes): Paginator;

    /**
     * View a note.
     *
     * @param User $context
     * @param int  $id
     *
     * @return Note
     * @throws AuthorizationException
     */
    public function viewNote(User $context, int $id): Note;

    /**
     * Create a note.
     *
     * @param User                 $context
     * @param User                 $owner
     * @param array<string, mixed> $attributes
     *
     * @return Note
     * @throws AuthorizationException
     * @see StoreRequest
     */
    public function createNote(User $context, User $owner, array $attributes): Note;

    /**
     * Update a note.
     *
     * @param User                 $context
     * @param int                  $id
     * @param array<string, mixed> $attributes
     *
     * @return Note
     * @throws AuthorizationException
     */
    public function updateNote(User $context, int $id, array $attributes): Note;

    /**
     * Delete a note.
     *
     * @param User $user
     * @param int  $id
     *
     * @return int
     * @throws AuthorizationException
     */
    public function deleteNote(User $user, int $id): int;

    /**
     * @param int $limit
     *
     * @return Paginator
     */
    public function findFeature(int $limit = 4): Paginator;

    /**
     * @param int $limit
     *
     * @return Paginator
     */
    public function findSponsor(int $limit = 4): Paginator;

    /**
     * @param User $context
     * @param int  $id
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function approve(User $context, int $id): bool;

    /**
     * @param Content $model
     *
     * @return bool
     */
    public function isPending(Content $model): bool;

    /**
     * @param User $user
     * @param int  $id
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function publish(User $user, int $id): bool;

    public function getWaitingTime(User $context, User $owner): int;
}
