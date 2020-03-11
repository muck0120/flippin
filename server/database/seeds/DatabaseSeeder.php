<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Card;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 5)->create();

        factory(User::class, 5)->create()->each(function ($user) {
            factory(Book::class, 5)->create([
                'user_id' => $user->user_id
            ]);
        });

        factory(User::class, 5)->create()->each(function ($user) {
            factory(Book::class, 5)->create([
                'user_id' => $user->user_id
            ])->each(function ($book) {
                factory(Card::class)->create([
                    'card_order' => 1,
                    'book_id' => $book->book_id
                ]);
                factory(Card::class)->create([
                    'card_order' => 2,
                    'book_id' => $book->book_id
                ]);
                factory(Card::class)->create([
                    'card_order' => 3,
                    'book_id' => $book->book_id
                ]);
                factory(Card::class)->create([
                    'card_order' => 4,
                    'book_id' => $book->book_id
                ]);
                factory(Card::class)->create([
                    'card_order' => 5,
                    'book_id' => $book->book_id
                ]);
            });
        });
    }
}
