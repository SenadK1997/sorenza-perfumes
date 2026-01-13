<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Livewire\ShopLivewire;
use App\Livewire\ProductShow;
use App\Livewire\CheckoutLivewire;
use App\Livewire\OrderSuccess;
use App\Livewire\OrderDetailLivewire;
use App\Livewire\TrackOrder;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/shop', ShopLivewire::class)->name('shop');
// Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{perfume}', ProductShow::class)
    ->name('products.show');

Route::get('/cart', \App\Livewire\CartPage::class);
Route::get('/checkout', CheckoutLivewire::class)->name('checkout');

Route::get('/order-success/{id}', OrderSuccess::class)->name('order.success');

Route::get('/track/{pretty_id}', OrderDetailLivewire::class)->name('order.track');

Route::get('/track-order', TrackOrder::class)->name('track.orders');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// require __DIR__.'/auth.php';
