<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'price',
        'author_id',
        'publication_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_date' => 'date',
    ];

    /**
     * A book belongs to an author (Many-to-One)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
