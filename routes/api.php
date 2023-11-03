<?php

use App\Http\Controllers\AdminView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Blog;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    //Users Data
    Route::get('/get_users', [AdminView::class, 'ViewUser'])->middleware('restrictRole:admin');
    Route::get('/get_blogs', [AdminView::class, 'ViewBlog'])->middleware('restrictRole:admin');
    Route::put('/act_user/{id}', [AdminView::class, 'ActiveUser'])->middleware('restrictRole:admin');

    //Kategori Data
    Route::get('/get_kategoris', [AdminView::class, 'Getkategori'])->middleware('restrictRole:admin');
    Route::post('/post_kategoris', [AdminView::class, 'Postkategori'])->middleware('restrictRole:admin');
    Route::put('/act_kategoris/{id}', [AdminView::class, 'Updatekategori'])->middleware('restrictRole:admin');
    Route::delete('/delete_kategoris/{id}', [AdminView::class, 'destroyKategori'])->middleware('restrictRole:admin');

    //Blog Data
    Route::get('/getall_blogs', [Blog::class, 'Getblog'])->middleware('restrictRole:penulis','restrictStatus:aktif');
    Route::post('/post_blog', [Blog::class, 'Postblog'])->middleware('restrictRole:penulis','restrictStatus:aktif');
    Route::get('/detail_blog/{id}', [Blog::class, 'get_detail_blog'])->middleware('restrictRole:penulis','restrictStatus:aktif');
    Route::put('/update_blog/{id}', [Blog::class, 'update'])->middleware('restrictRole:penulis','restrictStatus:aktif');
    Route::delete('/delete_blog/{id}', [Blog::class, 'destroy'])->middleware('restrictRole:penulis','restrictStatus:aktif');
});
