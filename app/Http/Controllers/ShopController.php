<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfume;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $perfumes = Perfume::all();

        return view('shop.index', compact('perfumes'));
    }
}
