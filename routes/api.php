<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public read-only routes (for demonstration/testing)
Route::get('authors', [AuthorController::class, 'index']);
Route::get('authors/{author}', [AuthorController::class, 'show']);
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

// Additional read-only route
Route::get('authors/{author}/books', function (Request $request, int $authorId) {
    $books = \App\Models\Book::where('author_id', $authorId)
        ->with('author')
        ->paginate($request->integer('per_page', 15));
    
    return \App\Http\Resources\BookResource::collection($books);
})->name('authors.books');

// Protected routes requiring authentication (using web auth)
Route::middleware(['auth'])->group(function () {
    // Authors write operations
    Route::post('authors', [AuthorController::class, 'store']);
    Route::put('authors/{author}', [AuthorController::class, 'update']);
    Route::delete('authors/{author}', [AuthorController::class, 'destroy']);
    
    // Books write operations
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
    Route::delete('books/{book}', [BookController::class, 'destroy']);
}); 