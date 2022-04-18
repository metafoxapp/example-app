<?php

namespace Company\Note\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use MetaFox\Platform\Http\Controllers\Api\ApiController;
use Company\Note\Http\Resources\v1\Note\NoteItemCollection as ItemCollection;
use Company\Note\Http\Resources\v1\Note\NoteDetail as Detail;
use Company\Note\Repositories\NoteRepositoryInterface;
use Company\Note\Http\Requests\v1\Note\IndexRequest;
use Company\Note\Http\Requests\v1\Note\StoreRequest;
use Company\Note\Http\Requests\v1\Note\UpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 | --------------------------------------------------------------------------
 |  Api Controller
 | --------------------------------------------------------------------------
 | stub: /modules/controllers/admin_api_controller.stub
 | Assign this class in $controllers of
 | @link \Company\Note\Http\Controllers\Api\NoteController::$controllers;
 */

/**
 * Class NoteAdminController.
 */
class NoteAdminController extends ApiController
{
    /**
     * @var NoteRepositoryInterface
     */
    public $repository;

    public function __construct(NoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  IndexRequest $request
     * @return mixed
     */
    public function index(IndexRequest $request)
    {
        $params = $request->validated();
        $data = $this->repository->get($params);

        return new ItemCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     *
     * @return Detail
     * @throws ValidatorException
     */
    public function store(StoreRequest $request)
    {
        $params = $request->validated();
        $data = $this->repository->create($params);

        return new Detail($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Detail
     */
    public function show($id)
    {
        $data = $this->repository->find($id);

        return new Detail($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest      $request
     * @param  int                $id
     * @return Detail
     * @throws ValidatorException
     */
    public function update(UpdateRequest $request, $id)
    {
        $params = $request->validated();
        $data = $this->repository->update($params, $id);

        return new Detail($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return $this->success([
            'id' => $id,
        ]);
    }
}
