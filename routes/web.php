<?php

use App\Http\Livewire\Apply;
use App\Http\Livewire\Degrees;
use App\Http\Livewire\Employment;
use App\Http\Livewire\Enroll;
use App\Http\Livewire\Work;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/work', Work::class)->name('work');

Route::middleware(['auth:sanctum', 'verified'])->get('/employment', [Employment::class, 'render'])->name('employment');

Route::middleware(['auth:sanctum', 'verified'])->get('/degrees', Degrees::class)->name('degrees');

Route::middleware(['auth:sanctum', 'verified'])->get('/apply/{id}', Apply::class)->name('apply');

Route::middleware(['auth:sanctum', 'verified'])->get('/enroll/{id}', Enroll::class)->name('enroll');
