<?php

use Illuminate\Support\Facades\Route;

// Public visitor routes
Route::get('/',        \App\Livewire\Home::class)->name('home');
Route::get('/dances',  \App\Livewire\ExploreDances::class)->name('dances');
Route::get('/attires', \App\Livewire\ExploreAttires::class)->name('attires');

// Auth
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login')->middleware('guest')->middleware('throttle:10,1');
Route::get('/forgot-password', \App\Livewire\Auth\ForgotPassword::class)
    ->name('password.request')->middleware('guest')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', \App\Livewire\Auth\ResetPassword::class)
    ->name('password.reset')->middleware('guest');
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
    Route::get('/showcase',  \App\Livewire\Admin\Showcase\ManageShowcase::class)->name('showcase');

    Route::get('/settings/accounts', \App\Livewire\Admin\Settings\ManageAccounts::class)->name('settings.accounts');
    Route::get('/settings/database', \App\Livewire\Admin\Settings\ManageDatabase::class)->name('settings.database');

    Route::get('/about',      \App\Livewire\Admin\About\AboutPage::class)->name('about');
    Route::get('/help',       \App\Livewire\Admin\About\HelpPage::class)->name('help');
    Route::get('/developer',  \App\Livewire\Admin\About\DeveloperPage::class)->name('developer');
});
