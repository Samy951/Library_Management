<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile.edit');

// Library Management Routes  
Route::middleware(['auth'])->group(function () {
    Route::view('authors', 'authors.index')->name('authors.index');
    Route::view('authors/create', 'authors.create')->name('authors.create');
    Route::view('authors/{author}/edit', 'authors.edit')->name('authors.edit');

    Route::view('books', 'books.index')->name('books.index');
    Route::view('books/create', 'books.create')->name('books.create');
    Route::view('books/{book}/edit', 'books.edit')->name('books.edit');
});

require __DIR__.'/auth.php';
