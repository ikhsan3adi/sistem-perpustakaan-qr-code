<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookStockSeeder extends Seeder
{
    public function run()
    {
        $books = $this->db->table('books')->get()->getResultArray();

        foreach ($books as $book) {
            $this->db->table('book_stock')->insert([
                'book_id' => $book['id'],
                'quantity' => 1
            ]);
        }
    }
}
