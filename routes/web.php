<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::resource('posts', PostsController::class);


Auth::routes();

// Route::get('/', function () {
//     // return view('welcome');
//     return view('home.index', []);
// })->name('home.index');

// Route::get('/contact', function() {
//     // return 'contact';
//     return view('home.contact');
// })->name('home.contact');

// Route::get('/single', AboutController::class);
// $posts = [
//   1 => [
//     'title' => 'Intro to Laravel',
//     'content' => 'This is a short intro to Laravel',
//     'is_new' => true,
//     'has_comments' => true
//   ],
//   2 => [
//     'title' => 'Intro to PHP',
//     'content' => 'This is a short intro to PHP',
//     'is_new' => false
//   ],
//   3 => [
//     'title' => 'Intro to Golang',
//     'content' => 'This is a short intro to Golang',
//     'is_new' => false
//   ]
// ];

// // Route::get('/posts', function() use ($posts) {

// //   // dd((int)request()->input('page', 1));
// //   // dd((int)request()->query('page', 1));
// //   /*
// //   request()->has();
// //   request()->whenHas();
// //   request()->hasAny();
// //   request()->filled()
// //   request()->whenFilled()
// //   */
// //   return view('posts.index', ['posts' => $posts]);
    
// // });

// // Route::get('/posts/{id}', function($id) use ($posts) {
// //       abort_if(!isset($posts[$id]), 404);

// //     return view('posts.show', ['post' => $posts[$id]]);
// // })
// // // ->where([
// // //     'id' => '[0-9]+'
// // // ])
// // ->name('posts.show');

// Route::get('/recents-posts/{days_ago?}', function($daysAgo = 20) {
//     return 'recent posts '. $daysAgo . ' days ago';
// })->name('posts.recent.inex')->middleware('auth');

// Route::prefix('/fun')->name('fun.')->group(function() use($posts) {
//   Route::get('/responses', function() use($posts) {
//     return response($posts, 201)
//     ->header('Content-Type', 'application/json')
//     ->cookie('MY-COOKIE', 'fade', 3600);
//   })->name('responses');
  
//   Route::get('/redirect', function() {
//     return redirect('/contact');
//   })->name('redirect');
  
//   Route::get('/back', function() {
//     return back();
//   })->name('back');
  
//   Route::get('/route', function() {
//     return redirect()->route('posts.show', ['id' => 1]);
//   })->name('route'); 
  
//   Route::get('/away', function() {
//     return redirect()->away('https://google..com');
//   })->name('away');
  
//   Route::get('/json', function() use($posts) {
//     return response()->json($posts);
//   })->name('json');
  
//   Route::get('/download', function() use($posts) {
//     return response()->download(public_path('/filename.jpg'), 'suggestedname.jpg');
//   })->name('download'); 
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
