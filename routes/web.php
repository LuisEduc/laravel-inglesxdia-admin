<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DiariopalabraController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PalabraController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\TipoController;


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

// DASHBOARD
Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('/lessons', LessonController::class);
    Route::resource('/categorias', CategoriaController::class);
    Route::resource('/preguntas', PreguntaController::class);
    Route::get('/preguntas/eliminar/{id}', 'App\Http\Controllers\PreguntaController@eliminar')->name('preguntas.eliminar');
    Route::resource('/tipos', TipoController::class);
    Route::post('/send-notification/{id}', 'App\Http\Controllers\PushuserController@sendNotification')->name('send.notification');
    Route::get('/palabras/reproducir', 'App\Http\Controllers\PalabraController@reproducir')->name('palabras.reproducir');
    Route::put('/palabras/grabar/{id}', 'App\Http\Controllers\PalabraController@grabar')->name('palabras.grabar');
    Route::get('/palabras/basico', 'App\Http\Controllers\PalabraController@basico')->name('palabras.basico');
    Route::get('/palabras/medio', 'App\Http\Controllers\PalabraController@medio')->name('palabras.medio');
    Route::get('/palabras/avanzado', 'App\Http\Controllers\PalabraController@avanzado')->name('palabras.avanzado');
    Route::resource('/palabras', PalabraController::class);
    Route::resource('/diariopalabras', DiariopalabraController::class);
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
