<?php

namespace Company\Note\Models;

use MetaFox\Platform\Contracts\Entity;
use MetaFox\Platform\Traits\Eloquent\Model\HasEntity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Company\Note\Database\Factories\CategoryFactory;

/**
 * Class Category.
 * @mixin Builder
 * @property int    $id
 * @property string $name
 * @property string $name_url
 * @property bool   $is_active
 * @property int    $ordering
 * @property int    $parent_id
 * @property int    $total_item
 * @property array  $subCategories
 * @property string $created_at
 * @property string $updated_at
 * @method static CategoryFactory factory()
 */
class Category extends Model implements Entity
{
    use HasEntity;
    use HasFactory;

    public const ENTITY_TYPE = 'note_category';

    protected $table = 'note_categories';

    public const IS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'is_active',
        'ordering',
        'parent_id',
        'name_url',
    ];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(
            Note::class,
            'note_category_data',
            'category_id',
            'item_id'
        )
            ->using(NoteCategoryData::class);
    }
}
