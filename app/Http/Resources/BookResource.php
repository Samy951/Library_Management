<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     title="Book", 
 *     description="Modèle représentant un livre",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identifiant unique du livre",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Titre du livre",
 *         maxLength=255,
 *         example="Les Misérables"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Prix du livre en euros",
 *         minimum=0,
 *         maximum=999.99,
 *         example=19.99
 *     ),
 *     @OA\Property(
 *         property="publication_date",
 *         type="string",
 *         format="date",
 *         description="Date de publication",
 *         example="1862-04-03"
 *     ),
 *     @OA\Property(
 *         property="author_id",
 *         type="integer",
 *         description="ID de l'auteur",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="author",
 *         ref="#/components/schemas/Author",
 *         description="Informations de l'auteur"
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
class BookResource extends JsonResource
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
            'title' => $this->title,
            'price' => $this->price,
            'publication_date' => $this->publication_date,
            'author_id' => $this->author_id,
            'author' => new AuthorResource($this->whenLoaded('author')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
