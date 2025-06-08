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

class BookApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur pour l'authentification
        $this->user = User::factory()->create();
        
        // Créer des auteurs pour les tests
        $this->authors = Author::factory(3)->create();
        
        // Créer des livres de test
        $this->books = Book::factory(5)->create([
            'author_id' => $this->authors->first()->id
        ]);
    }

    #[Test]
    public function it_can_list_all_books(): void
    {
        $response = $this->getJson('/api/books');

        $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'price',
                            'publication_date',
                            'author' => [
                                'id',
                                'first_name',
                                'last_name',
                                'full_name'
                            ],
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ])
                ->assertJsonCount(5, 'data');
    }

    #[Test]
    public function it_can_show_a_specific_book(): void
    {
        $book = $this->books->first();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $book->id,
                        'title' => $book->title,
                        'price' => $book->price,
                        'publication_date' => $book->publication_date->format('Y-m-d'),
                        'author' => [
                            'id' => $book->author->id,
                            'first_name' => $book->author->first_name,
                            'last_name' => $book->author->last_name,
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_a_new_book(): void
    {
        
        
        $bookData = [
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'publication_date' => $this->faker->date(),
            'author_id' => $this->authors->first()->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);

        $response->assertCreated()
                ->assertJson([
                    'data' => [
                        'title' => $bookData['title'],
                        'price' => $bookData['price'],
                        'publication_date' => $bookData['publication_date'],
                        'author' => [
                            'id' => $bookData['author_id']
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('books', [
            'title' => $bookData['title'],
            'author_id' => $bookData['author_id']
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_book()
    {
        
        
        $response = $this->actingAs($this->user)->postJson('/api/books', []);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['title', 'price', 'publication_date', 'author_id']);
    }

    /** @test */
    public function it_validates_title_max_length()
    {
        $bookData = [
            'title' => str_repeat('a', 256), // > 255 caractères
            'price' => 29.99,
            'publication_date' => '2023-01-01',
            'author_id' => $this->authors->first()->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_validates_price_is_numeric_and_positive()
    {
        // Test prix négatif
        $bookData = [
            'title' => 'Test Book',
            'price' => -10.50,
            'publication_date' => '2023-01-01',
            'author_id' => $this->authors->first()->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);
        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['price']);

        // Test prix non numérique
        $bookData['price'] = 'not-a-number';
        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);
        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['price']);
    }

    /** @test */
    public function it_validates_publication_date_format()
    {
        $bookData = [
            'title' => 'Test Book',
            'price' => 29.99,
            'publication_date' => 'invalid-date',
            'author_id' => $this->authors->first()->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['publication_date']);
    }

    /** @test */
    public function it_validates_author_exists()
    {
        $bookData = [
            'title' => 'Test Book',
            'price' => 29.99,
            'publication_date' => '2023-01-01',
            'author_id' => 9999, // ID inexistant
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $bookData);

        $response->assertUnprocessable()
                ->assertJsonValidationErrors(['author_id']);
    }

    #[Test]
    public function it_can_update_an_existing_book(): void
    {
        
        
        $book = $this->books->first();
        $newAuthor = $this->authors->last();
        
        $updateData = [
            'title' => 'Titre Mis à Jour',
            'price' => 45.99,
            'publication_date' => '2024-01-01',
            'author_id' => $newAuthor->id,
        ];

        $response = $this->actingAs($this->user)->putJson("/api/books/{$book->id}", $updateData);

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $book->id,
                        'title' => 'Titre Mis à Jour',
                        'price' => 45.99,
                        'publication_date' => '2024-01-01',
                        'author' => [
                            'id' => $newAuthor->id
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Titre Mis à Jour',
            'author_id' => $newAuthor->id
        ]);
    }

    /** @test */
    public function it_can_partially_update_a_book()
    {
        $book = $this->books->first();
        $originalTitle = $book->title;

        $response = $this->actingAs($this->user)->putJson("/api/books/{$book->id}", [
            'price' => 99.99,
        ]);

        $response->assertOk()
                ->assertJson([
                    'data' => [
                        'id' => $book->id,
                        'title' => $originalTitle, // Inchangé
                        'price' => 99.99,
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_when_updating_non_existent_book()
    {
        $response = $this->actingAs($this->user)->putJson('/api/books/999', [
            'title' => 'Test',
            'price' => 29.99,
        ]);

        $response->assertNotFound();
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        $book = $this->books->first();

        $response = $this->actingAs($this->user)->deleteJson("/api/books/{$book->id}");

        $response->assertOk()
                ->assertJson([
                    'message' => 'Livre supprimé avec succès.'
                ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_book()
    {
        $response = $this->actingAs($this->user)->deleteJson('/api/books/999');

        $response->assertNotFound();
    }

    /** @test */
    public function it_can_search_books_by_title()
    {
        Book::factory()->create([
            'title' => 'Les Misérables',
            'author_id' => $this->authors->first()->id
        ]);
        
        Book::factory()->create([
            'title' => 'Notre-Dame de Paris',
            'author_id' => $this->authors->first()->id
        ]);

        $response = $this->getJson('/api/books?search=Misérables');

        $response->assertOk();
        
        $books = $response->json('data');
        $this->assertCount(1, $books);
        $this->assertStringContainsString('Misérables', $books[0]['title']);
    }

    /** @test */
    public function it_can_filter_books_by_author()
    {
        $specificAuthor = $this->authors->last();
        Book::factory(2)->create(['author_id' => $specificAuthor->id]);

        $response = $this->getJson("/api/books?author_id={$specificAuthor->id}");

        $response->assertOk();
        
        $books = $response->json('data');
        $this->assertCount(2, $books);
        
        foreach ($books as $book) {
            $this->assertEquals($specificAuthor->id, $book['author']['id']);
        }
    }

    /** @test */
    public function it_can_filter_books_by_price_range()
    {
        // Nettoyer la base de données pour ce test spécifique
        Book::query()->delete();
        
        // Créer des livres avec des prix spécifiques
        Book::factory()->create(['price' => 15.99, 'author_id' => $this->authors->first()->id]);
        Book::factory()->create(['price' => 25.99, 'author_id' => $this->authors->first()->id]);
        Book::factory()->create(['price' => 35.99, 'author_id' => $this->authors->first()->id]);

        $response = $this->getJson('/api/books?min_price=20&max_price=30');

        $response->assertOk();
        
        $books = $response->json('data');
        $this->assertCount(1, $books);
        $this->assertEquals(25.99, $books[0]['price']);
    }

    /** @test */
    public function it_can_sort_books()
    {
        // Test tri par titre
        $response = $this->getJson('/api/books?sort=title&direction=asc');
        $response->assertOk();

        // Test tri par prix
        $response = $this->getJson('/api/books?sort=price&direction=desc');
        $response->assertOk();

        // Test tri par date de publication
        $response = $this->getJson('/api/books?sort=publication_date&direction=asc');
        $response->assertOk();
    }

    /** @test */
    public function it_can_paginate_books()
    {
        // Créer plus de livres pour tester la pagination
        Book::factory(20)->create(['author_id' => $this->authors->first()->id]);

        $response = $this->getJson('/api/books?per_page=5');

        $response->assertOk()
                ->assertJsonStructure([
                    'data',
                    'links' => ['first', 'last', 'prev', 'next'],
                    'meta' => ['current_page', 'per_page', 'total', 'last_page']
                ])
                ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_get_books_by_specific_author()
    {
        $author = $this->authors->last();
        $authorBooks = Book::factory(3)->create(['author_id' => $author->id]);

        $response = $this->getJson("/api/authors/{$author->id}/books");

        $response->assertOk()
                ->assertJsonCount(3, 'data');

        $books = $response->json('data');
        foreach ($books as $book) {
            $this->assertEquals($author->id, $book['author']['id']);
        }
    }

    /** @test */
    public function it_includes_author_relationship_in_book_responses()
    {
        $book = $this->books->first();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'author' => [
                            'id',
                            'first_name',
                            'last_name',
                            'full_name'
                        ]
                    ]
                ]);
    }
}
