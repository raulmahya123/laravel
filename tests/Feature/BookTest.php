<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_book()
    {
        // Arrange: Buat user admin
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Act: Kirim POST request sebagai admin
        $response = $this->actingAs($admin)->post(route('books.store'), [
            'title' => 'Test Book',
            'author' => 'Jake',
            'description' => 'Ini adalah buku uji coba.',
            'stock' => 10,
        ]);

        // Assert: Redirect dan data masuk DB
        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author' => 'Jake',
            'stock' => 10,
        ]);
    }

    public function test_non_admin_cannot_create_a_book()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->post(route('books.store'), [
            'title' => 'Unauthorized Book',
            'author' => 'Someone',
            'stock' => 5,
        ]);

        $response->assertStatus(403); // middleware is_admin harus kasih 403
        $this->assertDatabaseMissing('books', [
            'title' => 'Unauthorized Book',
        ]);
    }
}
