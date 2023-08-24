<?php

namespace Tests\Support\Models;

use App\Models\BookModel;
use Faker\Generator;

class BookFabricator extends BookModel
{
    public function fake(Generator &$faker)
    {
        $title = $faker->sentence($faker->numberBetween(2, 4));

        return [
            'slug'          => url_title($title, '-', true) . $faker->numberBetween(10000, 99999),
            'title'         => $title,
            'author'        => $faker->name,
            'publisher'     => $faker->company,
            'isbn'          => $faker->isbn13(),
            'year'          => $faker->year,
            'rack_id'       => $faker->numberBetween(1, 10),
            'category_id'   => $faker->numberBetween(1, 5),
            'book_cover'    => "book-{$faker->numberBetween(1, 10)}.jpg",
        ];
    }
}
