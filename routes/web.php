<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SitemapController;
use App\Livewire\ShopLivewire;
use App\Livewire\ProductShow;
use App\Livewire\CheckoutLivewire;
use App\Livewire\OrderSuccess;
use App\Livewire\OrderDetailLivewire;
use App\Livewire\TrackOrder;
use App\Livewire\WishlistPage;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Livewire\Customer\LoginPage as CustomerLoginPage;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\OrdersPage as CustomerOrdersPage;
use App\Livewire\Customer\AddressPage as CustomerAddressPage;
use App\Livewire\Customer\PasswordPage as CustomerPasswordPage;
use App\Livewire\Customer\ScentProfilePage as CustomerScentProfilePage;
use App\Livewire\Customer\MessagesPage as CustomerMessagesPage;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/shop', ShopLivewire::class)->name('shop');
// Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{perfume}', ProductShow::class)
    ->name('products.show');

Route::get('/cart', \App\Livewire\CartPage::class);
Route::get('/wishlist', WishlistPage::class)->name('wishlist');

// Local email previews — only reachable in local env, blocked in production.
if (app()->environment('local')) {
    Route::get('/_preview/email/order-placed/{pretty_id?}', function (?string $pretty_id = null) {
        $order = $pretty_id
            ? \App\Models\Order::with('perfumes')->where('pretty_id', $pretty_id)->firstOrFail()
            : \App\Models\Order::with('perfumes')->latest()->firstOrFail();

        return (new \App\Mail\OrderPlaced($order))->render();
    });
}
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

// --- Customer account (/nalog) ---
Route::prefix('nalog')->group(function () {
    // Guest routes
    Route::get('/prijava', CustomerLoginPage::class)->name('customer.login');
    Route::post('/prijava/link', [CustomerAuthController::class, 'sendMagicLink'])
        ->middleware('throttle:20,1')
        ->name('customer.login.send');
    Route::get('/prijava/link/{token}', [CustomerAuthController::class, 'consumeMagicLink'])
        ->middleware('throttle:30,1')
        ->name('customer.login.consume');
    Route::post('/prijava/lozinka', [CustomerAuthController::class, 'passwordLogin'])
        ->middleware('throttle:20,1')
        ->name('customer.login.password');

    // Auth routes
    Route::middleware('customer.auth')->group(function () {
        Route::get('/', CustomerDashboard::class)->name('customer.dashboard');
        Route::get('/narudzbe', CustomerOrdersPage::class)->name('customer.orders');
        Route::get('/profil-mirisa', CustomerScentProfilePage::class)->name('customer.scent');
        Route::get('/poruke', CustomerMessagesPage::class)->name('customer.messages');
        Route::get('/adresa', CustomerAddressPage::class)->name('customer.address');
        Route::get('/lozinka', CustomerPasswordPage::class)->name('customer.password');
        Route::post('/odjava', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    });
});

// require __DIR__.'/auth.php';
