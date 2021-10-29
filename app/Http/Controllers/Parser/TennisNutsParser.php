<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;

class TennisNutsParser extends Parser
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

        try {
            $html = file_get_contents($url);
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            $nodes = $xpath->evaluate('//div[@class="product-pricing"]');
            $htmlBlock = $dom->saveXML($nodes->item(0));
            $price = explode('£', $htmlBlock);
            $prices = explode(' ', $price[1]);
            return $this->isNumeric($prices[0]);
        } catch (\Exception $exception) {

            $html = HtmlDomParser::file_get_html($url);
            $element = $html->find('.product-multis', 0);
            if(!empty($element)) {
                $element = $element->find('.price', 0)->innertext;
                $price = preg_replace("/[^0-9.]/", "", $element);
                return $price;
            }
            return $this->checkValue("NIL");
        }
    }
}
