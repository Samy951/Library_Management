<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Liste des livres",
     *     description="Récupère la liste paginée des livres avec recherche, filtres et tri",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans titre ou nom d'auteur",
     *         required=false,
     *         @OA\Schema(type="string", example="Misérables")
     *     ),
     *     @OA\Parameter(
     *         name="author_id", 
     *         in="query",
     *         description="Filtrer par ID d'auteur",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Prix minimum",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=10.00)
     *     ),
     *     @OA\Parameter(
     *         name="max_price", 
     *         in="query",
     *         description="Prix maximum",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=50.00)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Champ de tri",
     *         required=false,
     *         @OA\Schema(type="string", enum={"title", "price", "publication_date", "author_name", "created_at"})
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Direction du tri",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
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
     *         description="Liste des livres",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $books = Book::query()
            ->with('author')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhereHas('author', function ($query) use ($search) {
                          $query->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%");
                      });
            })
            ->when($request->filled('author_id'), function ($query) use ($request) {
                $query->where('author_id', $request->integer('author_id'));
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->string('min_price'));
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->string('max_price'));
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                $sort = $request->string('sort');
                $direction = $request->string('direction', 'asc');
                
                if ($sort === 'author_name') {
                    $query->join('authors', 'books.author_id', '=', 'authors.id')
                          ->orderBy('authors.last_name', $direction)
                          ->orderBy('authors.first_name', $direction)
                          ->select('books.*');
                } else {
                    $query->orderBy($sort, $direction);
                }
            }, function ($query) {
                $query->orderBy('title');
            })
            ->paginate($request->integer('per_page', 15));

        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): BookResource
    {
        $book = Book::create($request->validated());

        return new BookResource($book->load('author'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): BookResource
    {
        return new BookResource($book->load('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $book->update($request->validated());

        return new BookResource($book->load('author'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'message' => 'Livre supprimé avec succès.'
        ]);
    }
}
