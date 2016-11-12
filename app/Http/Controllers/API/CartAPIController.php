<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCartAPIRequest;
use App\Http\Requests\API\UpdateCartAPIRequest;
use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Http\Criteria\UserIdCriteria;
/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class CartAPIController extends AppBaseController
{
    /** @var  CartRepository */
    private $cartRepository;
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(CartRepository $cartRepo, ProductRepository $productRepo)
    {
        $this->cartRepository = $cartRepo;
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cartRepository->pushCriteria(new RequestCriteria($request));
        $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->cartRepository->pushCriteria(new UserIdCriteria($request));
        $carts = $this->cartRepository->all();

        $products = array();
        foreach ($carts as $cart) {
            $product = Product::find($cart['product_id']);
            if ($product) {
                $product['cart_id'] = $cart['id'];
                $product['buy_price'] = $cart['buy_price'];

                $products[] = $product;

            }
        }

        return $this->sendResponse($products, 'Carts retrieved successfully');
    }

    /**
     * Store a newly created Cart in storage.
     * POST /carts
     *
     * @param CreateCartAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCartAPIRequest $request)
    {
        $input = $request->all();


        $productInput = $request->only(['item_id', 'name', 'image', 'repository_id', 'currency_unit', 'description']);
        $productInput['price'] = $request->input('buy_price');
        $checkProduct = Product::where([['item_id', '=' ,$productInput['item_id']], ['repository_id', '=', $productInput['repository_id']]])->first();
        if ($checkProduct) {
            $product = $checkProduct;
        } else {
            $product = $this->productRepository->create($productInput);
        }

        $cartInput = $request->only(['buy_price', 'user_id']);
        $cartInput['product_id'] = $product->id;

        $carts = $this->cartRepository->create($cartInput);

        return $this->sendResponse($carts->toArray(), 'Cart saved successfully');
    }

    /**
     * Display the specified Cart.
     * GET|HEAD /carts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        return $this->sendResponse($cart->toArray(), 'Cart retrieved successfully');
    }

    /**
     * Update the specified Cart in storage.
     * PUT/PATCH /carts/{id}
     *
     * @param  int $id
     * @param UpdateCartAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCartAPIRequest $request)
    {
        $input = $request->all();

        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        $cart = $this->cartRepository->update($input, $id);

        return $this->sendResponse($cart->toArray(), 'Cart updated successfully');
    }

    /**
     * Remove the specified Cart from storage.
     * DELETE /carts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        $cart->delete();

        return $this->sendResponse($id, 'Cart deleted successfully');
    }
}
