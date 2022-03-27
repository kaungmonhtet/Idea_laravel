<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;

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
    return redirect()->route('users.index');
});

// Route::get('/login', function () {
//     dd("HI");
//     return view('auth.login');
// });


Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('academic-years', AcademicController::class);
    Route::resource('users', UserController::class);
    Route::resource('ideas', IdeaController::class);
    Route::post('/comment/store', [CommentController::class,'store'])->name('comment.add');
    Route::patch('comment/{comment}/update', [CommentController::class,'update'])->name('comments.update');
    Route::post('/reaction/store', [CommentController::class,'reactionStore'])->name('reaction.add');
    Route::post('get-closure-date/', [AcademicController::class,'getClosure'])->name('get-closure-date');
    Route::get('reports', [AcademicController::class,'getClosure'])->name('reports.index');
    Route::get('ideas-by-closure', [IdeaController::class,'ideaListByFCDate'])->name('idea.closure');
    Route::get('download-zip', [IdeaController::class,'downloadZip'])->name('download-zip');

    Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
    Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');

    Route::get('ideas-per-department', [ReportController::class, 'ideaPerDepartment'])->name('ideas-per-department');
    Route::get('ideas-without-comment', [ReportController::class, 'ideaWithoutComment'])->name('ideas-without-comment');
    Route::get('anonymous-ideas', [ReportController::class, 'anonymousIdea'])->name('anonymous-ideas');
    Route::get('anonymous-comment', [ReportController::class, 'anonymousComment'])->name('anonymous-comment');

});

// Route::get('login', [CustomAuthController::class, 'index'])->name('login');