<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur pour l'authentification
        $this->user = User::factory()->create();
        
        // Créer quelques auteurs de test
        $this->authors = Author::factory(5)->create();
        
        // Créer des livres pour tester les relations
        Book::factory(3)->create(['author_id' => $this->authors->first()->id]);
    }

    #[Test]
    public function it_can_list_all_authors(): void
    {
        $response = $this->getJson('/api/authors');

        $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'first_name',
                            'last_name',
                            'full_name',
                            'books_count',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ])
                ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_show_a_specific_author()
    {
        $author = $this->authors->first();

        $response = $this->getJson("/api/authors/{$author->id}");

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $author->id,
                        'first_name' => $author->first_name,
                        'last_name' => $author->last_name,
                        'full_name' => $author->full_name,
                        'books_count' => 3, // 3 livres créés dans setUp
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_a_new_author()
    {
        
        
        $authorData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/authors', $authorData);

        $response->assertCreated()
                ->assertJson([
                    'data' => [
                        'first_name' => $authorData['first_name'],
                        'last_name' => $authorData['last_name'],
                        'books_count' => 0,
                    ]
                ]);

        $this->assertDatabaseHas('authors', $authorData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_author()
    {
        
        
        $response = $this->actingAs($this->user)->postJson('/api/authors', []);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['first_name', 'last_name']);
    }

    /** @test */
    public function it_validates_first_name_max_length()
    {
        
        
        $authorData = [
            'first_name' => str_repeat('a', 256), // > 255 caractères
            'last_name' => $this->faker->lastName,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/authors', $authorData);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['first_name']);
    }

    /** @test */
    public function it_validates_last_name_max_length()
    {
        
        
        $authorData = [
            'first_name' => $this->faker->firstName,
            'last_name' => str_repeat('a', 256), // > 255 caractères
        ];

        $response = $this->actingAs($this->user)->postJson('/api/authors', $authorData);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['last_name']);
    }

    /** @test */
    public function it_can_update_an_existing_author()
    {
        
        
        $author = $this->authors->first();
        $updateData = [
            'first_name' => 'Nouveau Prénom',
            'last_name' => 'Nouveau Nom',
        ];

        $response = $this->actingAs($this->user)->putJson("/api/authors/{$author->id}", $updateData);

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $author->id,
                        'first_name' => 'Nouveau Prénom',
                        'last_name' => 'Nouveau Nom',
                        'full_name' => 'Nouveau Prénom Nouveau Nom',
                    ]
                ]);

        $this->assertDatabaseHas('authors', array_merge(['id' => $author->id], $updateData));
    }

    /** @test */
    public function it_can_partially_update_an_author()
    {
        
        
        $author = $this->authors->first();
        $originalFirstName = $author->first_name;

        $response = $this->actingAs($this->user)->putJson("/api/authors/{$author->id}", [
            'last_name' => 'Nouveau Nom Seulement',
        ]);

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $author->id,
                        'first_name' => $originalFirstName, // Inchangé
                        'last_name' => 'Nouveau Nom Seulement',
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_when_updating_non_existent_author()
    {
        
        
        $response = $this->actingAs($this->user)->putJson('/api/authors/999', [
            'first_name' => 'Test',
            'last_name' => 'Test',
        ]);

        $response->assertNotFound();
    }

    /** @test */
    public function it_can_delete_an_author_without_books()
    {
        
        
        $authorWithoutBooks = Author::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/authors/{$authorWithoutBooks->id}");

        $response->assertOk()
                ->assertJson([
                    'message' => 'Auteur supprimé avec succès.'
                ]);

        $this->assertDatabaseMissing('authors', ['id' => $authorWithoutBooks->id]);
    }

    /** @test */
    public function it_cannot_delete_an_author_with_books()
    {
        
        
        $authorWithBooks = $this->authors->first(); // A 3 livres
        
        // Debug - Vérifier que l'auteur existe bien en base
        $this->assertDatabaseHas('authors', ['id' => $authorWithBooks->id]);
        
        // Vérifier que l'auteur a bien des livres
        $booksCount = $authorWithBooks->books()->count();
        $this->assertTrue($booksCount > 0, "L'auteur devrait avoir des livres mais en a $booksCount");

        $response = $this->actingAs($this->user)->deleteJson("/api/authors/{$authorWithBooks->id}");

        $response->assertUnprocessable()
                ->assertJson([
                    'message' => 'Impossible de supprimer cet auteur car il a des livres associés.',
                    'error' => 'AUTHOR_HAS_BOOKS'
                ]);

        $this->assertDatabaseHas('authors', ['id' => $authorWithBooks->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_author()
    {
        
        
        $response = $this->actingAs($this->user)->deleteJson('/api/authors/999');

        $response->assertNotFound();
    }

    /** @test */
    public function it_can_search_authors_by_name()
    {
        Author::factory()->create(['first_name' => 'Victor', 'last_name' => 'Hugo']);
        Author::factory()->create(['first_name' => 'Marcel', 'last_name' => 'Proust']);

        $response = $this->getJson('/api/authors?search=Victor');

        $response->assertOk();
        
        $authors = $response->json('data');
        $this->assertCount(1, $authors);
        $this->assertEquals('Victor', $authors[0]['first_name']);
    }

    /** @test */
    public function it_can_sort_authors()
    {
        // Test tri par prénom ascendant
        $response = $this->getJson('/api/authors?sort=first_name&direction=asc');
        $response->assertOk();

        // Test tri par nom descendant
        $response = $this->getJson('/api/authors?sort=last_name&direction=desc');
        $response->assertOk();
    }

    /** @test */
    public function it_can_paginate_authors()
    {
        // Créer plus d'auteurs pour tester la pagination
        Author::factory(20)->create();

        $response = $this->getJson('/api/authors?per_page=5');

        $response->assertOk()
                ->assertJsonStructure([
                    'data',
                    'links' => ['first', 'last', 'prev', 'next'],
                    'meta' => ['current_page', 'per_page', 'total', 'last_page']
                ])
                ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_includes_books_when_showing_author_details()
    {
        $author = $this->authors->first();

        $response = $this->getJson("/api/authors/{$author->id}");

        $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'books' => [
                            '*' => [
                                'id',
                                'title',
                                'price',
                                'publication_date'
                            ]
                        ]
                    ]
                ]);
    }
}
