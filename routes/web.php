<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('gh')->group(function () {
    Route::get('{user}/{repo}/{tag}/{file}', [FileController::class, 'serveFile'])->where(['file' => '.*']);
});

Route::prefix('gl')->group(function () {
    Route::get('{user}/{repo}/{tag}/{file}', [FileController::class, 'serveFile'])->where(['file' => '.*']);
});

// Route::prefix('bb')->group(function () {
//     Route::get('{user}/{repo}/{tag}/{file}', [FileController::class, 'serveFile'])->where(['file' => '.*']);;
// });

Route::get('/img/{domain}/{file}', [ImageController::class, 'show'])->where(['file' => '.*']);
