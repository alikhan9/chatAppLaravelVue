<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render(
        'Home'
    );
})->middleware(['auth', 'verified'])->name('home');

Route::get('/search', function () {
    return Inertia::render(
        'Search',
        [
        'users' => User::all()->where('name', 'like', '%'. request()->filter . '%')
    ]
    );
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
