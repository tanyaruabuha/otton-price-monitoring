<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PriceListUpdate\ObtainDataController;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function links($id)
    {
        $controller = new ObtainDataController();
        dd($controller->getCompetitorLinks($id));
    }

    public function values($id)
    {
        $controller = new ObtainDataController();
        dd($controller->obtain($id));
    }
}
