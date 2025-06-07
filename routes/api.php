<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authors API routes
Route::apiResource('authors', AuthorController::class);

// Books API routes  
Route::apiResource('books', BookController::class);

// Additional routes for specific needs
Route::get('authors/{author}/books', function (Request $request, int $authorId) {
    $books = \App\Models\Book::where('author_id', $authorId)
        ->with('author')
        ->paginate($request->integer('per_page', 15));
    
    return \App\Http\Resources\BookResource::collection($books);
})->name('authors.books'); 