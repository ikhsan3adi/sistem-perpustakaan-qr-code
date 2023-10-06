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
            'edition'       => null,
            'isbn'          => $faker->isbn13(),
            'year'          => $faker->year,
            'collation'     => $faker->sentence(1),
            'call_number'   => $faker->sentence(1),
            'language_id'   => 'id',
            'source'        => null,
            'book_cover'    => "book-{$faker->numberBetween(1, 10)}.jpg",
            'file_att'      => null,
            'author_id'     => $faker->numberBetween(1, 5),
            'publisher_id'  => $faker->numberBetween(1, 5),
            'place_id'      => $faker->numberBetween(1, 3),
        ];
    }
}
