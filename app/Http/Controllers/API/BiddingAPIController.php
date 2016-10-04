<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBiddingAPIRequest;
use App\Http\Requests\API\UpdateBiddingAPIRequest;
use App\Models\Bidding;
use App\Repositories\BiddingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class BiddingController
 * @package App\Http\Controllers\API
 */

class BiddingAPIController extends AppBaseController
{
    /** @var  BiddingRepository */
    private $biddingRepository;

    public function __construct(BiddingRepository $biddingRepo)
    {
        $this->biddingRepository = $biddingRepo;
    }

    /**
     * Display a listing of the Bidding.
     * GET|HEAD /biddings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->biddingRepository->pushCriteria(new RequestCriteria($request));
        $this->biddingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $biddings = $this->biddingRepository->all();

        return $this->sendResponse($biddings->toArray(), 'Biddings retrieved successfully');
    }

    /**
     * Store a newly created Bidding in storage.
     * POST /biddings
     *
     * @param CreateBiddingAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBiddingAPIRequest $request)
    {
        $input = $request->all();

        $biddings = $this->biddingRepository->create($input);

        return $this->sendResponse($biddings->toArray(), 'Bidding saved successfully');
    }

    /**
     * Display the specified Bidding.
     * GET|HEAD /biddings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Bidding $bidding */
        $bidding = $this->biddingRepository->findWithoutFail($id);

        if (empty($bidding)) {
            return $this->sendError('Bidding not found');
        }

        return $this->sendResponse($bidding->toArray(), 'Bidding retrieved successfully');
    }

    /**
     * Update the specified Bidding in storage.
     * PUT/PATCH /biddings/{id}
     *
     * @param  int $id
     * @param UpdateBiddingAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBiddingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Bidding $bidding */
        $bidding = $this->biddingRepository->findWithoutFail($id);

        if (empty($bidding)) {
            return $this->sendError('Bidding not found');
        }

        $bidding = $this->biddingRepository->update($input, $id);

        return $this->sendResponse($bidding->toArray(), 'Bidding updated successfully');
    }

    /**
     * Remove the specified Bidding from storage.
     * DELETE /biddings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Bidding $bidding */
        $bidding = $this->biddingRepository->findWithoutFail($id);

        if (empty($bidding)) {
            return $this->sendError('Bidding not found');
        }

        $bidding->delete();

        return $this->sendResponse($id, 'Bidding deleted successfully');
    }
}
