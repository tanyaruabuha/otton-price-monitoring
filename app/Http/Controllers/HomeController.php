<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductsUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $data = null;
        if (isset($products)) {
//            $data = $products->toArray()['data'];
            $data = $products->toArray();
            $keys = ['wd2', 'PCF_SMASHINN', 'PCF_TENNISWA', 'PCF_STRINGER', 'PCF_TENNISPO', 'PCF_APOLLOLE', 'PCF_TENNISNU'];
            foreach ($data as $name => $value) {
                $data[$name]['cheapest'] = 'wd2';
                $cheapest = $value['wd2'];
                foreach ($keys as $key) {
                    if (is_numeric($value[$key])) {
                        if ($value[$key] < $cheapest) {
                            $data[$name]['cheapest'] = $key;
                            $cheapest = $value[$key];
                        }
                    }
                }
            }
        }

        $updateItem = ProductsUpdate::orderBy('created_at', 'desc')->first();
        if (is_null($updateItem)) {
            $lastUpdate = 'never';
            $status = null;
        } else {
            $lastUpdate = $updateItem->created_at;
            $status =  $updateItem->status === 'updated' ?  null : $updateItem->status;
        }

        return view('home', [
            'products' => $products,
            'data' => $data,
            'lastUpdate' => $lastUpdate,
            'status' => $status,
            'count' => Product::count()
        ]);
    }

    public function count()
    {
        return Product::count();
    }
}
