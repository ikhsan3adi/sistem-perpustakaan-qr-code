<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class BookStockSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        $books = $this->db->table('books')->get()->getResultArray();

        foreach ($books as $book) {
            $this->db->table('book_stock')->insert([
                'book_id' => $book['id'],
                'quantity' => $faker->numberBetween(5, 100)
            ]);
        }
    }
}
