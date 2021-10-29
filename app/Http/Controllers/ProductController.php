<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductsUpdate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAll()
    {
        $products = Product::all();
        return $products;
    }
}
