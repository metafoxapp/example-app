<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Listeners;

use Company\Note\Models\Note;
use Company\Note\Repositories\NoteRepositoryInterface;

class UpdateFeedItemPrivacyListener
{
    /**
     * @param int    $itemId
     * @param string $itemType
     * @param int    $privacy
     * @param int[]  $list
     */
    public function handle(int $itemId, string $itemType, int $privacy, array $list = []): void
    {
        if ($itemType != Note::ENTITY_TYPE) {
            return;
        }

        $item = resolve(NoteRepositoryInterface::class)->find($itemId);
        $item->privacy = $privacy;
        $item->setPrivacyListAttribute($list);
        $item->save();
    }
}
