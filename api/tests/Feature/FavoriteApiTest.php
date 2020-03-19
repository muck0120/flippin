<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Favorite;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $book;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->book = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);
    }

    /**
     * お気に入りの登録ができることをテスト。
     *
     * @return void
     */
    public function testSuccessCreateFavorite()
    {
        $this->actingAs($this->user, 'api')
            ->postJson('/favorite'.'/'.$this->book->book_id)
            ->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->user_id,
            'book_id' => $this->book->book_id
        ]);
    }

    /**
     * お気に入りの削除ができることをテスト。
     *
     * @return void
     */
    public function testSuccessDeleteFavorite()
    {
        $favorite = factory(Favorite::class)->create([
            'user_id' => $this->user->user_id,
            'book_id' => $this->book->book_id
        ]);

        $this->actingAs($this->user, 'api')
            ->deleteJson('/favorite'.'/'.$favorite->book_id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $favorite->user_id,
            'book_id' => $favorite->book_id
        ]);
    }
}
