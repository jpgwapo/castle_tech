<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;

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
    return view('welcome');
});

Route::group(['middleware' => 'role'], function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::delete('/tasks/softDelete/{id}', [TaskController::class, 'softDelete']);
    Route::get('/deleted-tasks', [TaskController::class, 'softDeleted']);
    Route::get('/chart-tasks', [TaskController::class, 'chartIndex']);
    Route::patch('/tasks/restore/{id}', [TaskController::class, 'restore']);
    Route::resource('/sub-tasks', SubtaskController::class);
    Route::get('/sub-tasks-list/{id}', [SubtaskController::class, 'index']);
});
Route::resource('/tasks', TaskController::class);
Route::get('/user-dashboard', [TaskController::class, 'userDashboard']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
