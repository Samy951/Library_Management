<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Liste des auteurs",
     *     description="Récupère la liste paginée des auteurs avec recherche et tri",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans prénom/nom",
     *         required=false,
     *         @OA\Schema(type="string", example="Victor")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query", 
     *         description="Champ de tri",
     *         required=false,
     *         @OA\Schema(type="string", enum={"first_name", "last_name", "created_at"})
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
     *         description="Liste des auteurs",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Author")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $authors = Author::query()
            ->withCount('books')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%");
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                $sort = $request->string('sort');
                $direction = $request->string('direction', 'asc');
                $query->orderBy($sort, $direction);
            }, function ($query) {
                $query->orderBy('last_name')->orderBy('first_name');
            })
            ->paginate($request->integer('per_page', 15));

        return AuthorResource::collection($authors);
    }

    /**
     * @OA\Post(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Créer un nouvel auteur",
     *     description="Crée un nouvel auteur avec prénom et nom",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name"},
     *             @OA\Property(property="first_name", type="string", maxLength=255, example="Victor"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, example="Hugo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Auteur créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Author")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreAuthorRequest $request): AuthorResource
    {
        $author = Author::create($request->validated());

        return new AuthorResource($author->loadCount('books'));
    }

    /**
     * @OA\Get(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Afficher un auteur",
     *     description="Récupère les détails d'un auteur spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'auteur",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'auteur",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Author")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Auteur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Author]")
     *         )
     *     )
     * )
     */
    public function show(Author $author): AuthorResource
    {
        return new AuthorResource($author->load('books')->loadCount('books'));
    }

    /**
     * @OA\Put(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Mettre à jour un auteur",
     *     description="Met à jour les informations d'un auteur existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'auteur",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", maxLength=255, example="Victor"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, example="Hugo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Auteur mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Author")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Auteur non trouvé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function update(UpdateAuthorRequest $request, Author $author): AuthorResource
    {
        $author->update($request->validated());

        return new AuthorResource($author->loadCount('books'));
    }

    /**
     * @OA\Delete(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Supprimer un auteur",
     *     description="Supprime un auteur (impossible s'il a des livres)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'auteur",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Auteur supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Auteur supprimé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Suppression impossible (auteur a des livres)",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Impossible de supprimer cet auteur car il a des livres associés."),
     *             @OA\Property(property="error", type="string", example="AUTHOR_HAS_BOOKS")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Auteur non trouvé")
     * )
     */
    public function destroy(Author $author): JsonResponse
    {
        if ($author->books()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cet auteur car il a des livres associés.',
                'error' => 'AUTHOR_HAS_BOOKS'
            ], 422);
        }

        $author->delete();

        return response()->json([
            'message' => 'Auteur supprimé avec succès.'
        ]);
    }
}
