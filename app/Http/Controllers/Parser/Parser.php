<?php

namespace App\Http\Controllers\Parser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Parser extends Controller
{
    public function checkValue($value)
    {
        $value = strtoupper($value);

        if (is_null($value) || $value === "" || $value == 'NOT FOUND') {
            return "Not Sold";
        }
        if (is_numeric($value)) {
            return $value;
        }
        if ($value == "NIL") {
            return "-";
        }
        return null;
    }

    public function findUrl($string)
    {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);
        return $match[0][0];
    }

    public function findNumber($string)
    {
        return preg_replace("/[^0-9.]/", "", $string);
    }

    public function isNumeric($result)
    {
        return is_numeric(trim($result)) ? $result : 'NAN';
    }
}
