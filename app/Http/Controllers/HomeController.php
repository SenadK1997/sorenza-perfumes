<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $bestsellers = Perfume::visibleInShop()
            ->where('availability', true)
            ->where('is_bestseller', true)
            ->latest()
            ->take(8)
            ->get();

        // Fallback to latest available if no bestsellers marked yet
        if ($bestsellers->isEmpty()) {
            $bestsellers = Perfume::visibleInShop()
                ->where('availability', true)
                ->latest()
                ->take(8)
                ->get();
        }

        $shippingLabel = ShippingCalculator::summaryLabel();

        return view('home', compact('bestsellers', 'shippingLabel'));
    }
}
