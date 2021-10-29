<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TennisWarehouseEuropeParser extends Parser
{
    public function parse($link)
    {
        $checkedValue = $this->checkValue($link);
        if (isset($checkedValue)) return $checkedValue;

        try {
            $url = $this->findUrl($link);
        } catch (\Exception $exception) {
            return "Invalid url";
        }

        $client = new Client();
        $result = $client->request('POST', $url . '?vat=UK');
        $contents = (string)$result->getBody();
        $dom = new DOMDocument();
        @$dom->loadHTML($contents);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->evaluate('//div[@class="product_pricing"]');
        $htmlBlock = $dom->saveXML($nodes->item(0));
        $price = explode('>', $htmlBlock);
        $prices = explode('€', $price[3]);
        $prices = str_replace(",", ".", $prices[0]);

        $priceEUR = (double)$this->isNumeric($prices);
        $exchangeRates = json_decode($client->get('https://www.tenniswarehouse-europe.com/exchange.json')
            ->getBody()
            ->getContents())->rates;

        $rateGBP = $exchangeRates->GBP;
        $rateEUR = $exchangeRates->EUR;

        $priceGBP = ($priceEUR / $rateEUR) * $rateGBP;
        return number_format($priceGBP, 2, '.', '');
    }
}
