<?php

use App\Http\Controllers\AudioPlayController;
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
    return view('search_audio_play');
});


//Audio Play Routes

Route::get('audio_plays/create', [AudioPlayController::class, 'create']);
Route::post('audio_plays', [AudioPlayController::class, 'store']);
Route::get('audio_plays/{audio_play}/edit', [AudioPlayController::class, 'edit']);
Route::patch('audio_plays/{audio_play}', [AudioPlayController::class, 'update']);
