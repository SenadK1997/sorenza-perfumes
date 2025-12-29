<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfume;

class ShopController extends Controller
{
    public function index()
    {
        // Fetch all perfumes, you can add pagination later
        $perfumes = Perfume::all();

        // Pass perfumes to the view
        return view('shop.index', compact('perfumes'));
    }
}
