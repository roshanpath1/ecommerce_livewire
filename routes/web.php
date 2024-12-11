<?php

use App\Livewire\AboutUs;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckOutPage;
use App\Livewire\ContactPage;
use App\Livewire\HomePage;
use App\Livewire\MyAccount;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\Rating;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;


Route::get('/', HomePage::class)->name('home');
Route::get('/categories',CategoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/products/{slug}',ProductDetailPage::class);




Route::middleware('guest')->group(function(){
    Route::get('/login',LoginPage::class)->name('login');
Route::get('/register',RegisterPage::class);
Route::get('/forgot',ForgotPasswordPage::class)->name('password.request');
Route::get('/reset/{token}',ResetPasswordPage::class)->name('password.reset');

});

Route::middleware('auth')->group(function(){
    Route::get('/logout', function(){
        Auth::logout();
        // auth()->logout();
        return redirect('/');

    });
    Route::get('/checkout',CheckOutPage::class);
    Route::get('/my-orders',MyOrdersPage::class);
    Route::get('/my-orders/{order_id}',MyOrderDetailPage::class)->name('my-orders.show');

    Route::get('/success',SuccessPage::class)->name('success');
Route::get('/cancel',CancelPage::class)->name('cancel');
});

Route::get('/contact',ContactPage::class)->name('contact');
Route::get('/my-account',MyAccount::class)->name('my-account');
Route::get('/about-us',AboutUs::class)->name('about-us');
// Route::get('/rating',Rating::class,'add')->name('rating');


