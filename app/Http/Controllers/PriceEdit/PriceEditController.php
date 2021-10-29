<?php

namespace App\Http\Controllers\PriceEdit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PriceListUpdate\ObtainDataController;
use App\Http\Requests\PriceUpdateRequest;
use App\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceEditController extends Controller
{

    /**
     *
     *  Stage 7
     *
     * @param PriceUpdateRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws \ErrorException
     */
    public function edit(PriceUpdateRequest $request)
    {
        /*
         * Define variables
         */
        $productId = $request->productId;
        $price = $request->price;
        $service = $request->service;

        /*
         * Update on remote servers
         */
        $bigCommerceUpdateSuccess = $this->editBigCommercePrice($productId, $price, $service);
        $brightpearlUpdateSuccess = $this->editBrightpearlPrice($productId, $price, $service);

        /*
         * Update on local server
         */
        $obtainController = new ObtainDataController();
        $obtainedData = $obtainController->obtain($productId);
        Product::where('number', $productId)->update($obtainedData);

        return response()->json([
            'bigCommerceUpdateSuccess' => $bigCommerceUpdateSuccess,
            'brightpearlUpdateSuccess' => $brightpearlUpdateSuccess
        ]);
    }

    /**
     * Update Brightpearl
     *
     * @param $productId
     * @param $price
     * @param $service
     * @return bool
     * @throws GuzzleException
     */
    private function editBrightpearlPrice($productId, $price, $service)
    {
        try {

            $updated = false;

            $client = new Client();

            $priceListId = $service == 'UK' ? 2 : 8;

            $updateBrightpearlOptions = [
                'headers' => config('tokens.brightpearlAuthorisationToken'),
                'json' => [
                    'priceLists' => [
                        [
                            'priceListId' => $priceListId,
                            'quantityPrice' => [
                                '1' => $price / 1.2
                            ]
                        ]
                    ]
                ]
            ];

            $updateBrightpearlUrl = 'https://ws-eu1.brightpearl.com/public-api/racquetdepotuk/product-service/product-price/'
                . $productId . '/price-list';
            $updateBrightpearlPriceResponse = json_decode($client->request('PUT', $updateBrightpearlUrl, $updateBrightpearlOptions)
                ->getBody()
                ->getContents()
            );

            if (!(array)$updateBrightpearlPriceResponse) {
                $updated = true;
            }

            return $updated;

        } catch (\Exception $exception) {
            return 'Error';
        }
    }

    /**
     * Update BigCommerce
     *
     * @param $productId
     * @param $price
     * @param $service
     * @return bool
     */
    private function editBigCommercePrice($productId, $price, $service)
    {
        try {

            $updated = false;

            $client = new Client();

            // Get product
            $name = Product::where('number', $productId)->first()->pluck('name');

            $getBigCommerceProductOptions = [
                'headers' => config('tokens.bigcommerceAuthorisationToken')
            ];
            $getBigCommerceProductUrl = 'https://api.bigcommerce.com/stores/u71vk7f/v3/catalog/products';
            $getBigCommerceProductResponse = json_decode($client->get($getBigCommerceProductUrl, $getBigCommerceProductOptions)
                ->getBody()
                ->getContents()
            );

            $bigCommerceProducts = collect($getBigCommerceProductResponse->data)->first(function ($value) use ($name) {
                return $name === $value->name;
            });

            $bigCommerceProductID = $bigCommerceProducts->id ?? false;

            if ($bigCommerceProductID) {

                //Update product data
                $json = ['price' => $price];

                if ($service = 'WD') {
                    $json['retail_price'] = $price;
                }

                $updateBigCommerceProductOptions = [
                    'headers' => config('tokens.bigcommerceAuthorisationToken'),
                    'json' => $json
                ];
                $updateBigCommerceProductUrl = 'https://api.bigcommerce.com/stores/u71vk7f/v3/catalog/products/' . $bigCommerceProductID;
                $updateBigCommerceProductResponse = json_decode($client->put($updateBigCommerceProductUrl, $updateBigCommerceProductOptions)
                    ->getBody()
                    ->getContents()
                );

                if ($updateBigCommerceProductResponse->data->price = $price) {
                    $updated = true;
                }
            }
            return $updated;
        } catch (\Exception $exception) {
            return 'Error';
        }
    }

}
