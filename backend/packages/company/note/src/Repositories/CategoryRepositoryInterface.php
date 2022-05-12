<?php

namespace Company\Note\Repositories;

use MetaFox\Platform\Contracts\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\Paginator;
use Company\Note\Models\Category;
use MetaFox\Core\Repositories\Contracts\HasCategoryFormField;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Interface CategoryRepositoryInterface.
 * @method Category getModel()
 * @method Category find($id, $columns = ['*'])()
 */
interface CategoryRepositoryInterface extends HasCategoryFormField
{
    /**
     * @param User                 $context
     * @param array<string, mixed> $attributes
     *
     * @return Paginator
     * @throws AuthorizationException
     */
    public function viewCategories(User $context, array $attributes): Paginator;

    /**
     * @param User $context
     * @param int  $id
     *
     * @return Category
     * @throws AuthorizationException
     */
    public function viewCategory(User $context, int $id): Category;

    /**
     * @param User                 $context
     * @param array<string, mixed> $attributes
     *
     * @return Category
     * @throws AuthorizationException
     * @throws ValidatorException
     */
    public function createCategory(User $context, array $attributes): Category;

    /**
     * @param User $context
     * @param int  $id
     *
     * @param array<string, mixed> $attributes
     *
     * @return Category
     * @throws AuthorizationException
     */
    public function updateCategory(User $context, int $id, array $attributes): Category;

    /**
     * @param User $context
     * @param int  $id
     * @param int  $newCategoryId
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function deleteCategory(User $context, int $id, int $newCategoryId): bool;

    /**
     * @param Category $category
     * @param int      $newCategoryId
     *
     * @return bool
     */
    public function deleteOrMoveToNewCategory(Category $category, int $newCategoryId): bool;

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function deleteAllBelongTo(Category $category): bool;

    /**
     * @param Category $category
     * @param int      $newCategoryId
     *
     * @return bool
     */
    public function moveToNewCategory(Category $category, int $newCategoryId): bool;
}
