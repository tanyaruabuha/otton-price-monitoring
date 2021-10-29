<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;

class TwusaParser extends Parser
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
        $html = file_get_contents($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->evaluate('//div[@class="product_pricing"]');
        $htmlBlock = $dom->saveXML($nodes->item(0));
        $price = explode('$', $htmlBlock);
        $price = explode('>', $price[1]);
        $price1 = explode('<', $price[1]);

        $url    = "https://transferwise.com/ru/currency-converter/usd-to-gbp-rate?amount=1";
        $format = $this->getData($url);
        $price = str_replace(',','.',$format);
        $price = preg_replace('/[^0-9.]/', '', $price);
        $price = str_replace('"', '', $price);
        $priceResult = $price * $price1[0] * 1.2;
        $priceResult = round($priceResult, 2);
        return $this->isNumeric((string)$priceResult);
    }

    public function getData($url)
    {
        $html = file_get_contents($url);
        $dom  = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath     = new DOMXPath($dom);
        $nodes     = $xpath->evaluate('//span[@class="text-success"]');
        $blockhtml = $dom->saveXML($nodes->item(0));
        return $blockhtml;
    }
}
