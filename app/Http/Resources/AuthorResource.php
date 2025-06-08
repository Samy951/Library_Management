<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     title="Author",
 *     description="Modèle représentant un auteur",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identifiant unique de l'auteur",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="Prénom de l'auteur",
 *         maxLength=255,
 *         example="Victor"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Nom de famille de l'auteur",
 *         maxLength=255,
 *         example="Hugo"
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string",
 *         description="Nom complet de l'auteur",
 *         example="Victor Hugo"
 *     ),
 *     @OA\Property(
 *         property="books_count",
 *         type="integer",
 *         description="Nombre de livres de l'auteur",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de création",
 *         example="2024-01-01T00:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de dernière mise à jour",
 *         example="2024-01-01T00:00:00.000000Z"
 *     )
 * )
 */
class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'books_count' => $this->when($this->relationLoaded('books') || isset($this->books_count), 
                $this->books_count ?? $this->books->count()
            ),
            'books' => $this->whenLoaded('books'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
