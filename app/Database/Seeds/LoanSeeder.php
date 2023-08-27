<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'book_id' => 1,
                'quantity' => 1,
                'member_id' => 1,
                'uid' => sha1('1'),
                'loan_date' => '2023-08-21',
                'due_date' => '2023-08-28',
                'return_date' => null,
            ],
            [
                'id' => 2,
                'book_id' => 4,
                'quantity' => 1,
                'member_id' => 2,
                'uid' => sha1('2'),
                'loan_date' => '2023-08-13',
                'due_date' => '2023-08-20',
                'return_date' => null,
            ],
            [
                'id' => 3,
                'book_id' => 2,
                'quantity' => 5,
                'member_id' => 3,
                'uid' => sha1('3'),
                'loan_date' => '2023-08-13',
                'due_date' => '2023-08-20',
                'return_date' => '2023-08-24',
            ],
            [
                'id' => 4,
                'book_id' => 1,
                'quantity' => 1,
                'member_id' => 4,
                'uid' => sha1('4'),
                'loan_date' => '2023-08-7',
                'due_date' => '2023-08-21',
                'return_date' => '2023-08-24',
            ],
        ];

        $this->db->table('loans')->insertBatch($data);

        $this->call('FineSeeder');
    }
}
