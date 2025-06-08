<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_can_read_authors(): void
    {
        Author::factory()->create();

        $response = $this->getJson('/api/authors');
        
        $response->assertOk();
    }

    #[Test]
    public function guest_can_read_books(): void
    {
        $author = Author::factory()->create();
        Book::factory()->create(['author_id' => $author->id]);

        $response = $this->getJson('/api/books');
        
        $response->assertOk();
    }

    #[Test]
    public function guest_cannot_create_author(): void
    {
        $response = $this->postJson('/api/authors', [
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function guest_cannot_update_author(): void
    {
        $author = Author::factory()->create();

        $response = $this->putJson("/api/authors/{$author->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name'
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function guest_cannot_delete_author(): void
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson("/api/authors/{$author->id}");

        $response->assertUnauthorized();
    }

    #[Test]
    public function guest_cannot_create_book(): void
    {
        $author = Author::factory()->create();

        $response = $this->postJson('/api/books', [
            'title' => 'Test Book',
            'price' => 29.99,
            'publication_date' => '2023-01-01',
            'author_id' => $author->id
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function authenticated_user_can_create_author(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/api/authors', [
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);

        $response->assertCreated();
    }

    #[Test]
    public function authenticated_user_can_create_book(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/books', [
            'title' => 'Test Book',
            'price' => 29.99,
            'publication_date' => '2023-01-01',
            'author_id' => $author->id
        ]);

        $response->assertCreated();
    }

    #[Test]
    public function authenticated_user_can_update_author(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/authors/{$author->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name'
        ]);

        $response->assertOk();
    }

    #[Test]
    public function authenticated_user_can_delete_author_without_books(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/authors/{$author->id}");

        $response->assertOk();
    }
} 