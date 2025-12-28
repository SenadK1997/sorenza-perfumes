<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfume;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch 3 random perfumes from the database
        $perfumes = Perfume::inRandomOrder()->limit(3)->get();

        // Pass them to the view
        return view('home', compact('perfumes'));
    }
}
