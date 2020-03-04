<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;

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

        factory(User::class, 5)->create()
            ->each(function ($user) {
                $user->books()->createMany(
                    factory(Book::class, 2)->make()->toArray()
                );
            });
    }
}
