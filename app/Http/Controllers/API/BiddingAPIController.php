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
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Http\Criteria\UserIdCriteria;
/**
 * Class BiddingController
 * @package App\Http\Controllers\API
 */

class BiddingAPIController extends AppBaseController
{
    /** @var  BiddingRepository */
    private $biddingRepository;
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(BiddingRepository $biddingRepo, ProductRepository $productRepo)
    {
        $this->biddingRepository = $biddingRepo;
        $this->productRepository = $productRepo;
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
        $this->biddingRepository->pushCriteria(new UserIdCriteria($request));
        $biddings = $this->biddingRepository->all()->toArray();

        $products = array();
        foreach ($biddings as $bidding) {
            $product = Product::find($bidding['product_id']);
            if ($product) {
                $product['bid_price'] = $bidding['bid_price'];
                $product['bidding_id'] = $bidding['id'];
                $product['is_buy_automatically'] = $bidding['is_buy_automatically'];           
                $products[] = $product;
            }
        }

        return $this->sendResponse($products, 'Biddings retrieved successfully');
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

        $productInput = $request->only(['item_id', 'name', 'image', 'repository_id', 'currency_unit', 'description']);
        $productInput['price'] =  $request->input('last_price');
        $product = $this->productRepository->create($productInput);

        $biddingInput = $request->only(['bid_price', 'last_price', 'image', 'is_buy_automatically', 'user_id']);
        $biddingInput['product_id'] = $product['id'];
        $biddings = $this->biddingRepository->create($biddingInput);

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
