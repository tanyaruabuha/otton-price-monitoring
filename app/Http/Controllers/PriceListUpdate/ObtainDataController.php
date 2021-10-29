<?php

namespace App\Http\Controllers\PriceListUpdate;

use App\Http\Controllers\Controller;
use App\Product;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Parser\ParserController;


class ObtainDataController extends Controller
{
    private $guzzleClient;
    private $attempt = 1;
    private $timeout = 20;
    private $attemptsAllowed = 5;

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * Return all obtained data
     *
     * @param $productsUpdate
     * @return void
     * @throws GuzzleException
     */
    public function obtainAll($productsUpdate)
    {
        /**
         * Stage 1
         *
         *  Use API PRODUCT SEARCH to obtain:
         *  a. A list of all product IDs on the Brightpearl account.
         */
        $allProductId = $this->getAllProductId();
        $allProductIdCount = count($allProductId);

        $i = 1;
        foreach ($allProductId as $productId) {
           try {
               sleep(5);
//               $productsUpdate->status = 'in progress ' . $i . '/' . $allProductIdCount;
               $product = $this->obtain($productId);
               if($product) {
                   Product::insert($product);
                   $productsUpdate->save();
               }
               $i++;
           } catch (Exception $exception) {
               Log::debug('Product ID: ' . $productId . '; Exception: ' . $exception->getMessage());
           }
        }
    }

    /**
     * Entry point to the application
     *
     * @param null $productId
     * @return array|false
     * @throws GuzzleException
     * @throws \ErrorException
     */
    public function obtain($productId = null)
    {
        sleep(5);
        if (request()->has('productId')) $productId = request()->productId;

        /**
         * Stage 2
         *
         *  Then use API PRODUCT GET for each product ID to obtain:
         *      a. Product Name [salesChannels>productName]
         *      b. Product Category [categories>categoryCode]
         */
        $productNameAndCategoryCode = $this->getProductNameAndCategoryCode($productId);
        if (!$productNameAndCategoryCode) return [];

        $product = Product::where('name', $productNameAndCategoryCode)->first();
        if($product) return [];

        $categoryCode = $productNameAndCategoryCode['categoryCode'];

        /**
         *  Stage 3
         *
         *  Then use API PRODUCT CUSTOM FIELD GET for each product ID to obtain:
         *     a.   The links of the competitor product pages for the particular product
         *     i.   PCF_STRINGER = Stringers’ World
         *     ii.  PCF_TENNISNU = Tennis Nuts
         *     iii. PCF_TENNISPO = Tennis Point
         *     iv.  PCF_SMASHINN = Smash Inn
         *     v.   PCF_TENNISWA = Tennis Warehouse Europe
         */
        $this->attempt = 1;
        $competitorsLinks = $this->getCompetitorLinks($productId);


        /**
         * Stage 5
         *
         *  Then use API PRODUCT PRICE GET to obtain OUR selling price.
         */
        $this->attempt = 1;
        $productPrice = $this->getProductPrice($productId, $categoryCode);
        if($productPrice['priceWD1'] != '999999.00' && $productPrice['priceWD1'] != '99999.00') {
            /**
             * Stage 4
             *
             * Obtain the price from the above five websites. If there is no URL or the data
             *   says NOT FOUND, the output result should be “Not Sold”.
             */
            $parsedValues = $this->getParsedValues($competitorsLinks);

            $data = [
                "number" => $productId,
                "name" => $productNameAndCategoryCode['productName'],
                "rd" => $productPrice['priceRD'],
                "wd1" => $productPrice['priceWD1'],
                "wd2" => $productPrice['priceWD2'],
                "PCF_SMASHINN" => $parsedValues['smashInnValue'],
                "PCF_TENNISWA" => $parsedValues['tennisWarehouseEuropeValue'],
                "PCF_STRINGER" => $parsedValues['stringersWorldValue'],
                "PCF_TENNISPO" => $parsedValues['tennisPointValue'],
                "PCF_APOLLOLE" => $parsedValues['apolloleValue'],
                "PCF_TENNISNU" => $parsedValues['tennisNutsValue'],
                "PCF_FRAMEWOR" => $parsedValues['frameworkValue'],
                "PCF_TWUSA" => $parsedValues['twusaValue'],
            ];

            return $data;
        }
        return [];
    }

    /**
     * Stage 1
     *
     *  Use API PRODUCT SEARCH to obtain:
     *  a. A list of all product IDs on the Brightpearl account.
     *
     * @return Collection
     * @throws GuzzleException
     */
    public function getAllProductId()
    {

        $productSearchUrl = "https://ws-eu1.brightpearl.com/public-api/racquetdepotuk/product-service/product-search";
        $firstResult = 1;
        $productList = collect();
        $data = [];

        do {
            $request = $this->guzzleClient->request('GET', $productSearchUrl, [
                'headers' => config('tokens.brightpearlAuthorisationToken'),
                'query' => [
                    'firstResult' => $firstResult,
//                    'pageSize' => 20 // TODO: JUST FOR TEST
                ]
            ]);
            $response = json_decode($request->getBody());
            $results = $response->response->results;
            $productList = $productList->merge($results);
            $firstResult = $firstResult + 500;
            $morePagesAvailable = $response->response->metaData->morePagesAvailable;
//            break; // TODO: JUST FOR TEST
        } while ($morePagesAvailable);

        $allProductId = $productList->filter(function ($value) {
            return $value[16] == 'LIVE' || $value[16] == 'DISCONTINUED';
        })->pluck('0')->unique();

        return $allProductId;
    }

    /**
     * Stage 2
     *
     *  Then use API PRODUCT GET for each product ID to obtain:
     *      a. Product Name [salesChannels>productName]
     *      b. Product Category [categories>categoryCode]
     *
     * @param $productId
     * @return array|bool
     * @throws GuzzleException
     * @throws Exception
     */
    private function getProductNameAndCategoryCode($productId)
    {
        try {

            $getProductURL = "https://ws-eu1.brightpearl.com/public-api/racquetdepotuk/product-service/product/" . $productId;
            $getProductRequest = $this->guzzleClient->request('GET', $getProductURL,
                ['headers' => config('tokens.brightpearlAuthorisationToken')]);
            $getProductResponse = json_decode($getProductRequest->getBody());

            $productName = $getProductResponse->response[0]->salesChannels[0]->productName;

            if (empty($productName)) {
                return false;
            }

            $categoryCode = $getProductResponse->response[0]->salesChannels[0]->categories[0]->categoryCode;

            return [
                'productName' => $productName,
                'categoryCode' => $categoryCode
            ];

        } catch (ServerException $exception) {
            if ($exception->getCode() == 503 && $this->attempt <= $this->attemptsAllowed) {
                sleep($this->attempt * $this->timeout);
                $this->attempt++;
                $this->getProductNameAndCategoryCode($productId);
            } else {
                throw $exception;
            }
        }
    }

    /**
     *  Stage 3
     *
     *  Then use API PRODUCT CUSTOM FIELD GET for each product ID to obtain:
     *     a.   The links of the competitor product pages for the particular product
     *     i.   PCF_STRINGER = Stringers’ World
     *     ii.  PCF_TENNISNU = Tennis Nuts
     *     iii. PCF_TENNISPO = Tennis Point
     *     iv.  PCF_SMASHINN = Smash Inn
     *     v.   PCF_TENNISWA = Tennis Warehouse Europe
     *
     * @param $productId
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function getCompetitorLinks($productId)
    {
        try {

            $getCompetitorLinksUrl = "https://ws-eu1.brightpearl.com/public-api/racquetdepotuk/product-service/product-custom-field/" . $productId;
            $getCompetitorLinksRequest = $this->guzzleClient->request('GET', $getCompetitorLinksUrl,
                ['headers' => config('tokens.brightpearlAuthorisationToken')]);
            $getCompetitorLinksResponse = json_decode($getCompetitorLinksRequest->getBody());

            $competitorsLinks = $getCompetitorLinksResponse->response->{$productId};
            return $competitorsLinks;

        } catch (ServerException $exception) {
            if ($exception->getCode() == 503 && $this->attempt <= $this->attemptsAllowed) {
                sleep($this->attempt * $this->timeout);
                $this->attempt++;
                $this->getProductNameAndCategoryCode($productId);
            } else {
                throw $exception;
            }
        }

    }

    /**
     * Stage 4
     *
     * Obtain the price from the above five websites. If there is no URL or the data
     *   says NOT FOUND, the output result should be “Not Sold”.
     *
     * @param $competitorsLinks
     * @return array
     * @throws \ErrorException
     */
    private function getParsedValues($competitorsLinks)
    {
        $parser = new ParserController($competitorsLinks);

        $smashInnValue = $parser->getValue(ParserController::SMASH_INN);
        $tennisWarehouseEuropeValue = $parser->getValue(ParserController::TENNIS_WAREHOUSE_EUROPE);
        $stringersWorldValue = $parser->getValue(ParserController::STRINGERS_WORLD);
        $tennisPointValue = $parser->getValue(ParserController::TENNIS_POINT);
        $tennisNutsValue = $parser->getValue(ParserController::TENNIS_NUTS);
        $apolloleValue = $parser->getValue(ParserController::APOLLOLE);
        $frameworkValue = $parser->getValue(ParserController::PCF_FRAMEWOR);
        $twusaValue = $parser->getValue(ParserController::PCF_TWUSA);

        return [
            'smashInnValue' => $smashInnValue,
            'tennisWarehouseEuropeValue' => $tennisWarehouseEuropeValue,
            'stringersWorldValue' => $stringersWorldValue,
            'tennisPointValue' => $tennisPointValue,
            'tennisNutsValue' => $tennisNutsValue,
            'apolloleValue' => $apolloleValue,
            'frameworkValue' => $frameworkValue,
            'twusaValue' => $twusaValue,
        ];
    }

    /**
     * Stage 5
     *
     *  Then use API PRODUCT PRICE GET to obtain OUR selling price.
     *
     * @param $productId
     * @param $categoryCode
     * @return array
     * @throws GuzzleException
     */
    private function getProductPrice($productId, $categoryCode)
    {
        try {
            $getProductPriceUrl = "https://ws-eu1.brightpearl.com/public-api/racquetdepotuk/product-service/product-price/" . $productId;

            $getProductPriceRequest = $this->guzzleClient->request('GET', $getProductPriceUrl,
                ['headers' => config('tokens.brightpearlAuthorisationToken')]);
            $getProductPriceResponse = json_decode($getProductPriceRequest->getBody());

            /*
             * RD price
             */
            if (isset($getProductPriceResponse->response[0]->priceLists[1]->quantityPrice->{1})) {
                $priceRD = $getProductPriceResponse->response[0]->priceLists[1]->quantityPrice->{1} * 1.2;
            } else {
                $priceRD = 0;
            }

            /*
             * WD1 price
             */
            if (isset($getProductPriceResponse->response[0]->priceLists[6]->quantityPrice->{1})) {
                $priceWD1 = $getProductPriceResponse->response[0]->priceLists[6]->quantityPrice->{1};
            } else {
                $priceWD1 = 0;
            }

            /*
             * WD2 price
             */
            if ($categoryCode == 354 || $categoryCode == 346 || $categoryCode == 347) {
                $priceWD2 = $priceWD1 * 1.2;
            } else {
                $priceWD2 = $priceWD1 * 0.93 * 1.2;
            }

            $priceRD = number_format($priceRD, 2, '.', '');
            $priceWD1 = number_format($priceWD1, 2, '.', '');
            $priceWD2 = number_format($priceWD2, 2, '.', '');

            return [
                'priceRD' => $priceRD,
                'priceWD1' => $priceWD1,
                'priceWD2' => $priceWD2
            ];

        } catch (ServerException $exception) {
            if ($exception->getCode() == 503 && $this->attempt <= $this->attemptsAllowed) {
                sleep($this->attempt * $this->timeout);
                $this->attempt++;
                $this->getProductNameAndCategoryCode($productId);
            } else {
                throw $exception;
            }
        }
    }

}
