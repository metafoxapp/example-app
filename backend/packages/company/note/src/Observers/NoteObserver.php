<?php

namespace Company\Note\Observers;

use Exception;
use Company\Note\Models\Note;

/**
 * Class NoteObserver.
 */
class NoteObserver
{
    /**
     * @throws Exception
     */
    public function deleted(Note $note): void
    {
        $note->noteText()->delete();
        $note->tagData()->sync([]);
        $note->categories()->sync([]);

        if (null != $note->getThumbnail()) {
            deleteImageWithSize((string) $note->getThumbnail(), $note->getThumbnailServerId(), $note->getSizes(), false);
        }
    }
}
