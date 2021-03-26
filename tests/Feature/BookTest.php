<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_book()
    {
        $this->actingAs($this->user)
            ->postJson(route('books.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('books', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_book()
    {
        $book = Book::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('books.update', $book->id), [
                'name' => 'Updated book',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'name' => 'Updated book',
        ]);
    }

    /** @test */
    public function show_book()
    {
        $book = Book::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('books.show', $book->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $book->name,
                ]
            ]);
    }

    /** @test */
    public function list_book()
    {
        $books = Book::factory()->count(2)->create()->map(function ($book) {
            return $book->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('books.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $books->toArray()
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function delete_book()
    {
        $book = Book::factory()->create([
            'name' => 'Book for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('books.update', $book->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
            'name' => 'Book for delete',
        ]);
    }
}
