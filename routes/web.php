<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\TagController;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/blog',function(){
//     return view('blog.index',['blogs'=>Blog::all()]);
// });

// Route::get('/blog/{id}',function($id){
//     $blog=Blog::find($id);
//     return view('blog.show',['blog'=>$blog]);
// });

// Route::get('/blog',[BlogController::class,'index']);
// Route::get('/blog/create',[BlogController::class,'create']);

// Route::get('/blog/{blog}',[BlogController::class,'show']);
// Route::get('/blog/{blog}/edit',[BlogController::class,'edit']);

// Route::post('/blog', [BlogController::class,'store']);
// Route::delete('/blog/{blog}', [BlogController::class,'destroy']);
// Route::patch('/blog/{blog}', [BlogController::class,'update']);


Route::get('/blog/all',[BlogController::class,'all'])->name('blog.all');

Route::resource('blog',BlogController::class);

Route::resource('tag',TagController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

