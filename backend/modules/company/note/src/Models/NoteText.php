<?php

namespace Company\Note\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MetaFox\Platform\Contracts\ResourceText;
use MetaFox\Platform\Traits\Eloquent\Model\HasEntity;

/**
 * Class NoteText.
 *
 * @property int    $id
 * @property string $text
 * @property string $text_parsed
 *
 * @mixin Builder
 */
class NoteText extends Model implements ResourceText
{
    use HasEntity;

    public $timestamps = false;

    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'note_text';

    protected $fillable = [
        'text',
        'text_parsed',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'id', 'id');
    }
}

// end
