<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;

class StringersWorldParser extends Parser
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
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $html = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->evaluate('//div[@class="summary entry-summary"]//p[@class="price"]');
        $htmlBlock = $dom->saveXML($nodes->item(0));
        $pos      = strripos($htmlBlock, "€");
        if ($pos === false) {
            $pos1      = strripos($htmlBlock, "£");
            if ($pos1 === false) {
                return "Invalid url";
            } else {
                $price = explode('£', $htmlBlock);
                $price1 = explode('>', $price[1]);
                $price2 = explode('<', $price1[1]);
                return $this->isNumeric($price2[0]);
            }
        } else {
            $price = explode('€', $htmlBlock);
            $price1 = explode('>', $price[1]);
            $price2 = explode('<', $price1[1]);
            $url2    = "https://transferwise.com/ru/currency-converter/eur-to-gbp-rate?amount=1";
            $format = $this->getData($url2);
            $price = str_replace(',','.',$format);
            $price = preg_replace('/[^0-9.]/', '', $price);
            $price = str_replace('"', '', $price);
            $priceResult = $price * $price2[0];
            $priceResult = round($priceResult,2);
            return $this->isNumeric((string)$priceResult);
        }
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
