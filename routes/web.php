<?php

use Illuminate\Support\Facades\Route;

// Public visitor routes
Route::get('/',        \App\Livewire\Home::class)->name('home');
Route::get('/dances',  \App\Livewire\ExploreDances::class)->name('dances');
Route::get('/attires', \App\Livewire\ExploreAttires::class)->name('attires');

// Auth
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login')->middleware('guest')->middleware('throttle:10,1');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Admin routes (protected)
Route::get('/admin', fn () => redirect()->route('admin.dashboard'))->middleware('admin');
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/dances',    \App\Livewire\Admin\Dances\ManageDances::class)->name('dances');
    Route::get('/attires',   \App\Livewire\Admin\Attires\ManageAttires::class)->name('attires');
    Route::get('/guides',    \App\Livewire\Admin\Guides\ManageGuides::class)->name('guides');
});
