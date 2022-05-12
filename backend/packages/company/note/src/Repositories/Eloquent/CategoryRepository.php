<?php

namespace Company\Note\Repositories\Eloquent;

use Illuminate\Auth\Access\AuthorizationException;
use MetaFox\Platform\Contracts\User;
use MetaFox\Platform\Repositories\AbstractRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Gate;
use Company\Note\Jobs\DeleteCategoryJob;
use Company\Note\Models\Note;
use Company\Note\Models\Category;
use Company\Note\Policies\CategoryPolicy;
use Company\Note\Repositories\CategoryRepositoryInterface;

/**
 * Class NoteCategoryRepository.
 * @property Category $model
 * @method Category getModel()
 * @method Category find($id, $columns = ['*'])()
 */
class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    public function model(): string
    {
        return Category::class;
    }

    public function viewCategories(User $context, array $attributes): Paginator
    {
        policy_authorize(CategoryPolicy::class, 'viewAny', $context);

        $id = $attributes['id'];
        $limit = $attributes['limit'];

        $conditions = [['parent_id', '=', null]];

        if ($id > 0) {
            $conditions = [['id', '=', $attributes['id']]];
        }

        $relation = [
            'subCategories' => function (HasMany $query) {
                $query->where('is_active', Category::IS_ACTIVE);
            },
        ];

        return $this->getModel()->newQuery()
            ->with($relation)
            ->where($conditions)
            ->simplePaginate($limit);
    }

    public function getCategoriesForForm(User $context): array
    {
        policy_authorize(CategoryPolicy::class, 'viewAny', $context);

        $categories = $this->getModel()->newModelInstance()
            ->with([
                'subCategories' => function (HasMany $q) {
                    $q->where('is_active', Category::IS_ACTIVE)
                        ->orderBy('ordering');
                },
            ])
            ->whereNull('parent_id')
            ->where('is_active', Category::IS_ACTIVE)
            ->orderBy('ordering')
            ->get()
            ->collect();

        $categoriesData = [];
        $subCategories = [];

        foreach ($categories as $category) {
            /* @var Category $category */
            $categoriesData[] = [
                'label' => $category->name,
                'value' => $category->entityId(),
            ];

            foreach ($category->subCategories as $subCategory) {
                /* @var Category $subCategory */
                $subCategories[$category->entityId()][] = [
                    'label' => $subCategory->name,
                    'value' => $subCategory->entityId(),
                ];
            }
        }

        return [$categoriesData, $subCategories];
    }

    public function viewCategory(User $context, int $id): Category
    {
        policy_authorize(CategoryPolicy::class, 'view', $context);

        $relation = [
            'subCategories' => function (HasMany $query) {
                $query->where('is_active', Category::IS_ACTIVE);
            },
        ];

        return $this->with($relation)->find($id);
    }

    public function createCategory(User $context, array $attributes): Category
    {
        policy_authorize(CategoryPolicy::class, 'create', $context);
        /** @var Category $category */
        $category = parent::create($attributes);
        $category->refresh();

        return $category;
    }

    public function updateCategory(User $context, int $id, array $attributes): Category
    {
        policy_authorize(CategoryPolicy::class, 'update', $context);
        $category = $this->find($id);

        $category->fill($attributes)->save();
        $category->refresh();

        return $category;
    }

    public function deleteCategory(User $context, int $id, int $newCategoryId): bool
    {
        policy_authorize(CategoryPolicy::class, 'delete', $context);
        $category = $this->find($id);

        DeleteCategoryJob::dispatch($category, $newCategoryId);

        return true;
    }

    public function moveToNewCategory(Category $category, int $newCategoryId): bool
    {
        $newCategory = $this->find($newCategoryId);
        $noteIds = $category->notes()->pluck('notes.id')->toArray();

        //Move note
        if (!empty($noteIds)) {
            $newCategory->notes()->sync($noteIds, false);
        }

        //update parent_id
        Category::query()->where('parent_id', '=', $category->id)->update(['parent_id' => $newCategory->id]);

        return true;
    }

    public function deleteAllBelongTo(Category $category): bool
    {
        $category->notes()->each(function (Note $note) {
            $note->delete();
        });

        $category->subCategories()->each(function (Category $item) {
            DeleteCategoryJob::dispatch($item, 0);
        });

        return true;
    }

    public function deleteOrMoveToNewCategory(Category $category, int $newCategoryId): bool
    {
        if ($newCategoryId) {
            $this->moveToNewCategory($category, $newCategoryId);

            return (bool) $category->delete();
        }

        $this->deleteAllBelongTo($category);

        return (bool) $category->delete();
    }
}
