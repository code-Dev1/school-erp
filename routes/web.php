<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Guardian\GuardianCreate;
use App\Livewire\Pages\Guardian\GuardianIndex;
use App\Livewire\Pages\Student\StudentCreate;
use App\Livewire\Pages\Student\StudentIndex;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', Login::class)
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/guardian-create', GuardianCreate::class)->name('guardian-create');
    Route::get('/guardian-index', GuardianIndex::class)->name('guardian-index');
    Route::get('/student-create', StudentCreate::class)->name('student-create');
    Route::get('/student-index', StudentIndex::class)->name('student-index');
});
