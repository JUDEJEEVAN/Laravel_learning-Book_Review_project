<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class)->only(['index' , 'show']);

Route::resource('books.reviews', ReviewController::class)
    ->scoped([ 'review' => 'book' ])
    ->only(['create', 'store']);

// scoped([ 'review' => 'book' ]) this function places the review in the scope of book which means laravel will look for the relationship and won't let anyone access any review without a book linked to it.
