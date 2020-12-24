<?php
namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Buyers
 */
Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionController', ['only' => ['index', 'show']]);
Route::resource('buyers.products', 'Buyer\BuyerProductController', ['only' => ['index', 'show']]);
Route::resource('buyers.sellers', 'Buyer\BuyerSellerController', ['only' => ['index', 'show']]);
Route::resource('buyers.categories', 'Buyer\BuyerCategoryController', ['only' => ['index', 'show']]);


/**
 * Categories
 */
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('categories.products', 'Category\CategoryProductController', ['except' => ['create', 'edit']]);
Route::resource('categories.sellers', 'Category\CategorySellerController', ['except' => ['create', 'edit']]);
Route::resource('categories.transactions', 'Category\CategoryTransactionController', ['except' => ['create', 'edit']]);
Route::resource('categories.buyers', 'Category\CategoryBuyerController', ['except' => ['create', 'edit']]);

/**
 * Products
 */
Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);
Route::resource('products.transactions', 'Product\ProductTransactionController', ['only' => ['index', 'show']]);
Route::resource('products.buyers', 'Product\ProductBuyerController', ['only' => ['index', 'show']]);
Route::resource('products.categories', 'Product\ProductCategoryController', ['except' => ['create', 'edit']]);
Route::resource('products.buyers.transactions', 'Product\ProductBuyerTransactionController', ['only' => ['store']]);

/**
 * Transactions
 */
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('transactions.categories', 'Transaction\TransactionCategoryController', ['only' => ['index', 'show']]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellerController', ['only' => ['index', 'show']]);


/**
 * Sellers
 */
Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);
Route::resource('sellers.transactions', 'Seller\SellerTransactionController', ['only' => ['index', 'show']]);
Route::resource('sellers.categories', 'Seller\SellerCategoryController', ['only' => ['index', 'show']]);
Route::resource('sellers.buyers', 'Seller\SellerBuyerController', ['only' => ['index', 'show']]);
Route::resource('sellers.products', 'Seller\SellerProductController', ['except' => ['create', 'show', 'edit']]);

/**
 * Users
 */
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::get('users/verify/{token}', 'User\UserController@verify')->name('verify');
Route::get('users/{user}/resend', 'User\UserController@resend')->name('resend');


Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
