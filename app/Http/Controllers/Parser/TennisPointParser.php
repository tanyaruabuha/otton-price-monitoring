<?php

namespace App\Http\Controllers\Parser;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;

class TennisPointParser extends Parser
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
        $nodes = $xpath->evaluate('//span[@itemprop="price"]');
        $htmlBlock = $dom->saveXML($nodes->item(0));
        $price = explode('£', $htmlBlock);
        return $this->isNumeric(strip_tags($price[1]));
    }
}
