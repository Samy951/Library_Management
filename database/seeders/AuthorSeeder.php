<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['first_name' => 'Victor', 'last_name' => 'Hugo'],
            ['first_name' => 'Émile', 'last_name' => 'Zola'],
            ['first_name' => 'Marcel', 'last_name' => 'Proust'],
            ['first_name' => 'Albert', 'last_name' => 'Camus'],
            ['first_name' => 'Gustave', 'last_name' => 'Flaubert'],
            ['first_name' => 'Stendhal', 'last_name' => 'Beyle'],
            ['first_name' => 'Honoré', 'last_name' => 'Balzac'],
            ['first_name' => 'Alexandre', 'last_name' => 'Dumas'],
            ['first_name' => 'Jules', 'last_name' => 'Verne'],
            ['first_name' => 'Simone', 'last_name' => 'Beauvoir'],
            ['first_name' => 'Jean-Paul', 'last_name' => 'Sartre'],
            ['first_name' => 'Marguerite', 'last_name' => 'Duras'],
            ['first_name' => 'André', 'last_name' => 'Malraux'],
            ['first_name' => 'François', 'last_name' => 'Mauriac'],
            ['first_name' => 'Anatole', 'last_name' => 'France'],
        ];

        foreach ($authors as $authorData) {
            Author::create($authorData);
        }
    }
}
