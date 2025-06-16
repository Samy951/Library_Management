<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

// Public read-only routes (for demonstration/testing)
Route::get('authors', [AuthorController::class, 'index']);
Route::get('authors/{author}', [AuthorController::class, 'show']);
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

/**
 * @OA\Get(
 *     path="/api/authors/{id}/books",
 *     tags={"Authors"},
 *     summary="Livres d'un auteur",
 *     description="Récupère tous les livres d'un auteur spécifique",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'auteur",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Éléments par page",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des livres de l'auteur",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book"))
 *         )
 *     )
 * )
 */
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