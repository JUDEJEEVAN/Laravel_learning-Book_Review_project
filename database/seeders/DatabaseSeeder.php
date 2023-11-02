<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Book::factory(33)->create()->each(function ($book) {
            $numOfReviews = random_int(5, 30);

            Review::factory()->count($numOfReviews)->good()->for($book)->create();
        });
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        Book::factory(33)->create()->each(function ($book) {
            $numberOfReviews = random_int(5, 30);

            Review::factory()->count($numberOfReviews)->average()->for($book)->create();
        });


        Book::factory(30)->create()->each(function ($book) {
            $numberOfReviews = random_int(5,20);

            Review::factory()->count($numberOfReviews)->bad()->for($book)->create();
        });
    }
}
