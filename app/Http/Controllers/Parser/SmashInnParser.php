<?php

namespace App\Http\Controllers\Parser;

use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;

class SmashInnParser extends Parser
{
    public function parse($link)
    {
        $checkedValue = $this->checkValue($link);
        if (isset($checkedValue)) return $checkedValue;

        try {

            $url = $this->findUrl($link);

            $html = HtmlDomParser::file_get_html($url);
            $element = $html->find('#total_dinamic', 0)->innertext;

            $price = preg_replace("/[^0-9.]/", "", $element);

        } catch (\Exception $exception) {
            return "Invalid url";
        }

        return $price;
    }
}
