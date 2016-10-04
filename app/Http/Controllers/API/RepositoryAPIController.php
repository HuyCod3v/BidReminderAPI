<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRepositoryAPIRequest;
use App\Http\Requests\API\UpdateRepositoryAPIRequest;
use App\Models\Repository;
use App\Repositories\RepositoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RepositoryController
 * @package App\Http\Controllers\API
 */

class RepositoryAPIController extends AppBaseController
{
    /** @var  RepositoryRepository */
    private $repositoryRepository;

    public function __construct(RepositoryRepository $repositoryRepo)
    {
        $this->repositoryRepository = $repositoryRepo;
    }

    /**
     * Display a listing of the Repository.
     * GET|HEAD /repositories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->repositoryRepository->pushCriteria(new RequestCriteria($request));
        $this->repositoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        $repositories = $this->repositoryRepository->all();

        return $this->sendResponse($repositories->toArray(), 'Repositories retrieved successfully');
    }

    /**
     * Store a newly created Repository in storage.
     * POST /repositories
     *
     * @param CreateRepositoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRepositoryAPIRequest $request)
    {
        $input = $request->all();

        $repositories = $this->repositoryRepository->create($input);

        return $this->sendResponse($repositories->toArray(), 'Repository saved successfully');
    }

    /**
     * Display the specified Repository.
     * GET|HEAD /repositories/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Repository $repository */
        $repository = $this->repositoryRepository->findWithoutFail($id);

        if (empty($repository)) {
            return $this->sendError('Repository not found');
        }

        return $this->sendResponse($repository->toArray(), 'Repository retrieved successfully');
    }

    /**
     * Update the specified Repository in storage.
     * PUT/PATCH /repositories/{id}
     *
     * @param  int $id
     * @param UpdateRepositoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRepositoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var Repository $repository */
        $repository = $this->repositoryRepository->findWithoutFail($id);

        if (empty($repository)) {
            return $this->sendError('Repository not found');
        }

        $repository = $this->repositoryRepository->update($input, $id);

        return $this->sendResponse($repository->toArray(), 'Repository updated successfully');
    }

    /**
     * Remove the specified Repository from storage.
     * DELETE /repositories/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Repository $repository */
        $repository = $this->repositoryRepository->findWithoutFail($id);

        if (empty($repository)) {
            return $this->sendError('Repository not found');
        }

        $repository->delete();

        return $this->sendResponse($id, 'Repository deleted successfully');
    }
}
