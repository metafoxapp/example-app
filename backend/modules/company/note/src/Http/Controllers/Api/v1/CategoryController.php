<?php

namespace Company\Note\Http\Controllers\Api\v1;

use MetaFox\Platform\Http\Controllers\Api\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Company\Note\Http\Requests\v1\Category\DeleteRequest;
use Company\Note\Http\Requests\v1\Category\IndexRequest;
use Company\Note\Http\Requests\v1\Category\StoreRequest;
use Company\Note\Http\Requests\v1\Category\UpdateRequest;
use Company\Note\Http\Resources\v1\Category\CategoryDetail;
use Company\Note\Http\Resources\v1\Category\CategoryItemCollection;
use Company\Note\Repositories\CategoryRepositoryInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoryController.
 */
class CategoryController extends ApiController
{
    public CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     * @throws AuthorizationException|AuthenticationException
     */
    public function index(IndexRequest $request)
    {
        $data = $this->repository->viewCategories(user(), $request->validated());

        return $this->success(new CategoryItemCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidatorException|AuthenticationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $category = $this->repository->createCategory(user(), $request->validated());

        return $this->success(new CategoryDetail($category));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthorizationException|AuthenticationException
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->repository->viewCategory(user(), $id);

        return $this->success(new CategoryDetail($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int           $id
     *
     * @return JsonResponse
     * @throws AuthorizationException|AuthenticationException
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->repository->updateCategory(user(), $id, $request->validated());

        return $this->success(new CategoryDetail($category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRequest $request
     * @param int           $id
     *
     * @return JsonResponse
     * @throws AuthorizationException|AuthenticationException
     */
    public function destroy(DeleteRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $newCategoryId = $params['new_category_id'];
        $this->repository->deleteCategory(user(), $id, $newCategoryId);

        return $this->success([
            'id' => $id,
        ]);
    }
}
