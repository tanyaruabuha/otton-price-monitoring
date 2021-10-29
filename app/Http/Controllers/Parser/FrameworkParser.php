<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;

class FrameworkParser extends Parser
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
        $nodes = $xpath->evaluate('//div[@class="product-info-price"]//span[@class="price"]');
        $htmlBlock = $dom->saveXML($nodes->item(0));
        $price = explode('£', $htmlBlock);
        $price1 = explode('>', $price[1]);
        $price2 = explode('<', $price1[0]);
        return $this->isNumeric($price2[0]);
    }

}
