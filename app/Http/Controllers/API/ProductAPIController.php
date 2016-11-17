<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */

class ProductAPIController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->productRepository->pushCriteria(new RequestCriteria($request));
        $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
        $products = $this->productRepository->all();

        $client = new Client([
            'timeout'  => 10.0,
        ]);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 1);
        $name = $request->get('name');
        if (empty($name) || $name == "") {
            return $this->sendResponse($products, 'Products retrieved successfully');
        } else {
            $response = $client->request('GET', 'http://svcs.ebay.com/services/search/FindingService/v1'
                                           . '?OPERATION-NAME=findItemsByKeywords'
                                           . '&SERVICE-VERSION=1.0.0'
                                           . '&SECURITY-APPNAME=HuyHaQua-BidRemin-PRD-02f4f54aa-9267511c'
                                           . '&RESPONSE-DATA-FORMAT=JSON'
                                           . '&GLOBAL-ID=EBAY-US'
                                           . '&keywords='. $name
                                           . '&paginationInput.pageNumber=' . $offset
                                           . '&paginationInput.entriesPerPage=' . $limit);

            $content = $response->getBody()->getContents();
            
            $result = json_decode($content, true);
            $findItemsByKeywordsResponse = $result['findItemsByKeywordsResponse'];
            $searchResult = $findItemsByKeywordsResponse[0]['searchResult'];
            $count = $searchResult[0]['@count'];
            $item = $searchResult[0]['item'];

            $products = array();
            for ($index = 0; $index < $count; $index++) {
                $products[$index]['item_id'] = $item[$index]['itemId'][0];
                $products[$index]['name'] = $item[$index]['title'][0];
                $products[$index]['image'] = $item[$index]['galleryURL'][0];
                $products[$index]['price'] = $item[$index]['sellingStatus'][0]['currentPrice'][0]['__value__'];
                $products[$index]['currency_unit'] = $item[$index]['sellingStatus'][0]['currentPrice'][0]['@currencyId'];
                $products[$index]['repository_id'] = 1;
            }

            return $this->sendResponse($products, 'Products retrieved successfully');
        }
    }

    /**
     * Store a newly created Product in storage.
     * POST /products
     *
     * @param CreateProductAPIRequest $request
     *hp
     * @return Response
     */
    public function store(CreateProductAPIRequest $request)
    {
        $input = $request->all();

        $products = $this->productRepository->create($input);

        return $this->sendResponse($products->toArray(), 'Product saved successfully');
    }

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse($product->toArray(), 'Product retrieved successfully');
    }

    /**
     * Update the specified Product in storage.
     * PUT/PATCH /products/{id}
     *
     * @param  int $id
     * @param UpdateProductAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductAPIRequest $request)
    {
        $input = $request->all();

        /** @var Product $product */
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $product = $this->productRepository->update($input, $id);

        return $this->sendResponse($product->toArray(), 'Product updated successfully');
    }

    /**
     * Remove the specified Product from storage.
     * DELETE /products/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $product->delete();

        return $this->sendResponse($id, 'Product deleted successfully');
    }

    
}
