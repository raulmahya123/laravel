<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;


class BorrowingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_borrow_book()
    {
        $user = \App\Models\User::factory()->create();
        $book = \App\Models\Book::factory()->create(['stock' => 1]);
    
        $this->actingAs($user)
             ->post(route('books.borrow', $book))
             ->assertRedirect();
    
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);
    }
    
}
