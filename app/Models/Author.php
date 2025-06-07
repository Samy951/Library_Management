<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
    ];

    /**
     * An author can have many books (One-to-Many)
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Accessor for full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
