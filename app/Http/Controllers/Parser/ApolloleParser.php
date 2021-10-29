<?php

namespace App\Http\Controllers\Parser;

use Illuminate\Http\Request;

class ApolloleParser extends Parser
{
    public function parse($link)
    {
        $checkedValue = $this->checkValue($link);
//        if (!empty($this->findNumber($link))) {
//            return $this->findNumber($link);
//        }
        if (isset($checkedValue)) {
            if (is_numeric($checkedValue)) {
                $value = ($checkedValue * 0.95) * 1.2;
                return number_format($value, 2, '.', '');
            }
            return $checkedValue;
        }
        return null;
    }
}
