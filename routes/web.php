<?php

use App\Http\Controllers\RoomChatController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('room/create', [RoomController::class, 'create'])
        ->name('room.create');
    Route::get('room/{room}', [RoomController::class, 'show'])
        ->name('room.show');

    Route::post('room/{room}/chat', [RoomChatController::class, 'store'])
        ->name('room.chat.store');
});
