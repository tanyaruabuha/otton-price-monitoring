<?php

namespace App\Http\Controllers\PriceListUpdate;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductsUpdate;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class PriceListUpdateController extends Controller
{
    private $obtainInstance;

    public function __construct()
    {
        $this->obtainInstance = new ObtainDataController();
    }

    /*
     * Entry point to custom price update
     */
    public function index()
    {
        return view('update');
    }

    /**
     * Method updates data from database with data on the server
     *
     * @return void
     * @throws GuzzleException
     */
    public function update()
    {
        $productsUpdate = new ProductsUpdate();
        $productsUpdate->status = 'in progress';
        $productsUpdate->save();
        $productsUpdate->refresh();

        /*
      * Delete all records in products table
      */
        Product::truncate();

        /*
         * Obtain all products
         */
        $this->obtainInstance->obtainAll($productsUpdate);

        /*
         * Change update status
         */
        $productsUpdate->status = 'updated';
        $productsUpdate->save();
    }

    /**
     * Add product to database, api(add-product)
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws \ErrorException
     */
    public function addProduct(Request $request)
    {
        $request->validate([
            'productId' => 'required'
        ]);
        $product = $this->obtainInstance->obtain($request->productId);
        if($product) {
            Product::insert($product);
        }
        return response()->json($product);
    }

    /**
     * Method truncate products table and return all product id from server
     *       api(truncate-and-get-all-product-id)
     *
     * @return Collection
     * @throws GuzzleException
     */
    public function truncateAndGetAllProductId()
    {
        $productUpdate = new ProductsUpdate();
        $productUpdate->status = 'updated manually';
        $productUpdate->save();

        Product::truncate();
        return $this->obtainInstance->getAllProductId();
    }
}
