<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Card;
use App\Models\Favorite;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * 問題集の新規作成ができることをテスト。
     *
     * @return void
     */
    public function testSuccessCreateBook()
    {
        $createBook = factory(Book::class)->make();

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/books/book', [
                'book_title' => $createBook->book_title,
                'book_desc' => $createBook->book_desc,
                'book_is_publish' => $createBook->book_is_publish
            ]);

        $this->assertDatabaseHas('books', [
            'user_id' => $this->user->user_id,
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'book' => [
                    'user_id' => $this->user->user_id,
                    'book_title' => $createBook->book_title,
                    'book_desc' => $createBook->book_desc,
                    'book_is_publish' => $createBook->book_is_publish
                ]
            ]);
    }

    /**
     * 未ログインで問題集の新規作成が失敗することをテスト。
     *
     * @return void
     */
    public function testFailCreateBookByNotLogin()
    {
        $createBook = factory(Book::class)->make();

        $this->postJson('/books/book', [
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ])->assertStatus(401);

        $this->assertDatabaseMissing('books', [
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);
    }

    /**
     * 問題集の新規作成が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailCreateBook
     * @param string $title
     * @param string $desc
     * @param boolean $isPublish
     * @return void
     */
    public function testFailCreateBook($title, $desc, $isPublish)
    {
        $this->actingAs($this->user, 'api')
            ->postJson('/books/book', [
                'book_title' => $title,
                'book_desc' => $desc,
                'book_is_publish' => $isPublish
            ])->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'book_title' => $title,
            'book_desc' => $desc,
            'book_is_publish' => $isPublish
        ]);
    }

    public function dataproviderFailCreateBook()
    {
        return [
            'book_title なし' => ['', str_repeat('a', 200), true],
            'book_title 50文字以上' => [str_repeat('a', 51), str_repeat('a', 200), true],
            'book_desc 200文字以上' => [str_repeat('a', 50), str_repeat('a', 201), true],
            'book_is_publis なし' => [str_repeat('a', 50), str_repeat('a', 200), null],
            'book_is_publis boolean以外' => [str_repeat('a', 50), str_repeat('a', 200), 'a'],
        ];
    }

    /**
     * 既存問題集の更新ができることをテスト。
     *
     * @return void
     */
    public function testSuccessUpdateBook()
    {
        $createBookId = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ])->book_id;

        $updateBook = factory(Book::class)->make();

        $response = $this->actingAs($this->user, 'api')
            ->putJson('/books'.'/'.$createBookId, [
                'book_title' => $updateBook->book_title,
                'book_desc' => $updateBook->book_desc,
                'book_is_publish' => $updateBook->book_is_publish
            ]);

        $this->assertDatabaseHas('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $updateBook->book_title,
            'book_desc' => $updateBook->book_desc,
            'book_is_publish' => $updateBook->book_is_publish
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'book' => [
                    'book_id' => $createBookId,
                    'user_id' => $this->user->user_id,
                    'book_title' => $updateBook->book_title,
                    'book_desc' => $updateBook->book_desc,
                    'book_is_publish' => $updateBook->book_is_publish
                ]
            ]);
    }

    /**
     * 未ログインで既存問題集の更新が失敗することをテスト。
     *
     * @return void
     */
    public function testFailUpdateBookByNotLogin()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);
        $createBookId = $createBook->book_id;

        $updateBook = factory(Book::class)->make();

        $this->putJson('/books'.'/'.$createBookId, [
                'book_title' => $updateBook->book_title,
                'book_desc' => $updateBook->book_desc,
                'book_is_publish' => $updateBook->book_is_publish
            ])->assertStatus(401);

        $this->assertDatabaseHas('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);
    }

    /**
     * 作成者と更新者が異なると問題集の更新が失敗することをテスト。
     *
     * @return void
     */
    public function testFailUpdateBookByUnauthorizedUser()
    {
        $createBookId = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ])->book_id;

        $updateBook = factory(Book::class)->make();

        $unauthorizedUser = factory(User::class)->create();

        $this->actingAs($unauthorizedUser, 'api')
            ->putJson('/books'.'/'.$createBookId, [
                'book_title' => $updateBook->book_title,
                'book_desc' => $updateBook->book_desc,
                'book_is_publish' => $updateBook->book_is_publish
            ])->assertRedirect('/unauthorized');

        $this->assertDatabaseMissing('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $updateBook->book_title,
            'book_desc' => $updateBook->book_desc,
            'book_is_publish' => $updateBook->book_is_publish
        ]);
    }

    /**
     * 問題集の更新が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailUpdateBook
     * @param string $title
     * @param string $desc
     * @param string $isPublish
     * @return void
     */
    public function testFailUpdateBook($title, $desc, $isPublish)
    {
        $createBookId = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ])->book_id;

        $this->actingAs($this->user, 'api')
            ->putJson('/books'.'/'.$createBookId, [
                'book_title' => $title,
                'book_desc' => $desc,
                'book_is_publish' => $isPublish
            ])->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $title,
            'book_desc' => $desc,
            'book_is_publish' => $isPublish
        ]);
    }

    public function dataproviderFailUpdateBook()
    {
        return [
            'book_title なし' => ['', str_repeat('a', 200), true],
            'book_title 50文字以上' => [str_repeat('a', 51), str_repeat('a', 200), true],
            'book_desc 200文字以上' => [str_repeat('a', 50), str_repeat('a', 201), true],
            'book_is_publis なし' => [str_repeat('a', 50), str_repeat('a', 200), null],
            'book_is_publis boolean以外' => [str_repeat('a', 50), str_repeat('a', 200), 'a'],
        ];
    }

    /**
     * 問題集の削除が成功することをテスト。
     *
     * @return void
     */
    public function testSuccessDeleteBook()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);

        $createBookId = $createBook->book_id;

        $this->actingAs($this->user, 'api')
            ->deleteJson('/books'.'/'.$createBookId)
            ->assertStatus(200);

        $this->assertDatabaseMissing('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);
    }

    /**
     * 作成者と削除者が異なると問題集の削除が失敗することをテスト。
     *
     * @return void
     */
    public function testFailDeleteBookByUnauthorizedUser()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);

        $createBookId = $createBook->book_id;

        $unauthorizedUser = factory(User::class)->create();

        $this->actingAs($unauthorizedUser, 'api')
            ->deleteJson('/books'.'/'.$createBookId)
            ->assertRedirect('/unauthorized');

        $this->assertDatabaseHas('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);
    }

    /**
     * 未ログインで問題集の削除が失敗することをテスト。
     *
     * @return void
     */
    public function testFailDeleteBookByNotLogin()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);

        $createBookId = $createBook->book_id;

        $this->deleteJson('/books'.'/'.$createBookId)
            ->assertStatus(401);

        $this->assertDatabaseHas('books', [
            'book_id' => $createBookId,
            'user_id' => $this->user->user_id,
            'book_title' => $createBook->book_title,
            'book_desc' => $createBook->book_desc,
            'book_is_publish' => $createBook->book_is_publish
        ]);
    }

    /**
     * [単体取得]未ログインで「公開」かつ「問題あり」の問題集が取得できることをテスト。
     *
     * @return void
     */
    public function testSuccessGetBookInPublish()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id,
            'book_is_publish' => true
        ]);
        factory(Card::class)->create([
            'card_order' => 1,
            'book_id' => $createBook->book_id
        ]);

        $createBookId = $createBook->book_id;

        $this->getJson('/books'.'/'.$createBookId)
            ->assertStatus(200)
            ->assertJson([
                'book' => [
                    'book_id' => $createBookId,
                    'user_id' => $this->user->user_id,
                    'book_title' => $createBook->book_title,
                    'book_desc' => $createBook->book_desc,
                    'book_is_publish' => $createBook->book_is_publish
                ]
            ]);
    }

    /**
     * [単体取得]未ログインで「非公開」問題集が取得できないことをテスト。
     *
     * @return void
     */
    public function testFailGetBookInPrivate()
    {
        $createBookId = factory(Book::class)->create([
            'user_id' => $this->user->user_id,
            'book_is_publish' => false
        ])->book_id;

        $this->getJson('/books'.'/'.$createBookId)
            ->assertRedirect('/notfound');
    }

    /**
     * [単体取得]ログイン中に他の人が作成した「公開」問題集が取得できることをテスト。
     *
     * @return void
     */
    public function testSuccessGetBookInPublishByLoginUser()
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id,
            'book_is_publish' => true
        ]);
        factory(Card::class)->create([
            'card_order' => 1,
            'book_id' => $createBook->book_id
        ]);

        $createBookId = $createBook->book_id;

        $loginUser = factory(User::class)->create();

        $this->actingAs($loginUser, 'api')
            ->getJson('/books'.'/'.$createBookId)
            ->assertStatus(200)
            ->assertJson([
                'book' => [
                    'book_id' => $createBookId,
                    'user_id' => $this->user->user_id,
                    'book_title' => $createBook->book_title,
                    'book_desc' => $createBook->book_desc,
                    'book_is_publish' => $createBook->book_is_publish
                ]
            ]);
    }

    /**
     * [単体取得]ログイン中に他の人が作成した「非公開」問題集が取得できないことをテスト。
     *
     * @return void
     */
    public function testFailGetBookInPrivateByLoginUser()
    {
        $createBookId = factory(Book::class)->create([
            'user_id' => $this->user->user_id,
            'book_is_publish' => false
        ])->book_id;

        $loginUser = factory(User::class)->create();

        $this->actingAs($loginUser, 'api')
            ->getJson('/books'.'/'.$createBookId)
            ->assertRedirect('/notfound');
    }

    /**
     * [単体取得]ログイン中に自分が作成した問題集は全て取得できることをテスト。
     *
     * @dataProvider dataproviderSuccessGetMyBook
     * @param boolean $isPublish
     * @return void
     */
    public function testSuccessGetMyBook($isPublish)
    {
        $createBook = factory(Book::class)->create([
            'user_id' => $this->user->user_id,
            'book_is_publish' => $isPublish
        ]);

        $createBookId = $createBook->book_id;

        $this->actingAs($this->user, 'api')
            ->getJson('/books'.'/'.$createBookId)
            ->assertStatus(200)
            ->assertJson([
                'book' => [
                    'book_id' => $createBookId,
                    'user_id' => $this->user->user_id,
                    'book_title' => $createBook->book_title,
                    'book_desc' => $createBook->book_desc,
                    'book_is_publish' => $createBook->book_is_publish
                ]
            ]);
    }

    public function dataproviderSuccessGetMyBook()
    {
        return [
            '公開' => [true],
            '非公開' => [false]
        ];
    }

    /**
     * [複数取得]あらゆるパターンで問題集が取得できることをテスト。
     *
     * @dataProvider dataproviderSuccessGetBooks
     * @param string $group
     * @param boolean $isLogin
     * @return void
     */
    public function testSuccessGetBooks($group, $isLogin)
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $dataproviderBooks = [
            0 => ['isPublish' => true, 'createdBy' => $user1, 'favoriteBy' => $user1],
            1 => ['isPublish' => true, 'createdBy' => $user1, 'favoriteBy' => $user2],
            2 => ['isPublish' => true, 'createdBy' => $user1, 'favoriteBy' => null],
            3 => ['isPublish' => true, 'createdBy' => $user2, 'favoriteBy' => $user1],
            4 => ['isPublish' => true, 'createdBy' => $user2, 'favoriteBy' => $user2],
            5 => ['isPublish' => true, 'createdBy' => $user2, 'favoriteBy' => null],
            6 => ['isPublish' => false, 'createdBy' => $user1, 'favoriteBy' => $user1],
            7 => ['isPublish' => false, 'createdBy' => $user1, 'favoriteBy' => $user2],
            8 => ['isPublish' => false, 'createdBy' => $user1, 'favoriteBy' => null],
            9 => ['isPublish' => false, 'createdBy' => $user2, 'favoriteBy' => $user1],
            10 => ['isPublish' => false, 'createdBy' => $user2, 'favoriteBy' => $user2],
            11 => ['isPublish' => false, 'createdBy' => $user2, 'favoriteBy' => null],
        ];

        $books = [];
        foreach ($dataproviderBooks as $key => $value) {
            $books[$key] = factory(Book::class)->create([
                'user_id' => $value['createdBy']->user_id,
                'book_is_publish' => $value['isPublish']
            ]);
            factory(Card::class)->create([
                'card_order' => 1,
                'book_id' => $books[$key]->book_id
            ]);
            if (!is_null($value['favoriteBy'])) {
                factory(Favorite::class)->create([
                    'user_id' => $value['favoriteBy'],
                    'book_id' => $books[$key]->book_id
                ]);
            }
            $books[$key] = $books[$key]->toArray();
        }

        $response = null;
        if ($isLogin) {
            $response = $this->actingAs($user1, 'api')
                ->getJson('/books'.'/'.$group)
                ->assertStatus(200);
        } else {
            $response = $this->getJson('/books'.'/'.$group)
                ->assertStatus(200);
        }

        $booksWithinCheckParams = function ($numbers) use ($books) {
            $vals = [];
            foreach ($numbers as $number) {
                array_push($vals, [
                    'book_id' => $books[$number]['book_id'],
                    'book_title' => $books[$number]['book_title'],
                    'book_desc' => $books[$number]['book_desc']
                ]);
            }
            return $vals;
        };

        if ($group === 'others' && !$isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([0, 1, 2, 3, 4, 5])
            ]);
            $response->assertJsonMissing([
                'data' => $booksWithinCheckParams([6, 7, 8, 9, 10, 11])
            ]);
        }
        else if ($group === 'others' && $isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([3, 4, 5])
            ]);
            $response->assertJsonMissing([
                'data' => $booksWithinCheckParams([9, 10, 11])
            ]);
        }
        else if ($group === 'mines' && !$isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([])
            ]);
        }
        else if ($group === 'mines' && $isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([0, 1, 2, 6, 7, 8])
            ]);
            $response->assertJsonMissing([
                'data' => $booksWithinCheckParams([3, 4, 5, 9, 10, 11])
            ]);
        }
        else if ($group === 'favorites' && !$isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([])
            ]);
        }
        else if ($group === 'favorites' && $isLogin) {
            $response->assertJson([
                'data' => $booksWithinCheckParams([0, 3, 6])
            ]);
            $response->assertJsonMissing([
                'data' => $booksWithinCheckParams([1, 2, 4, 5, 7, 8, 9, 10, 11])
            ]);
        }
    }

    public function dataproviderSuccessGetBooks()
    {
        return [
            'others, logout' => ['others', false],
            'others, login' => ['others', true],
            'mines, logout' => ['mines', false],
            'mines, login' => ['mines', true],
            'favorites, logout' => ['favorites', false],
            'favorites, login' => ['favorites', true]
        ];
    }
}
