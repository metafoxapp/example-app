<?php

namespace Company\Note\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CategoryData.
 *
 * @property int $id
 * @property int $category_id
 * @property int $item_id
 *
 * @mixin Builder
 */
class CategoryData extends Pivot
{
    public $timestamps = false;

    protected $table = 'note_category_data';

    protected $fillable = [
        'category_id',
        'item_id',
    ];
}

// end
