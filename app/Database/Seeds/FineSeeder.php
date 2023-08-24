<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FineSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'loan_id'       => 3,
                'amount_paid'   => null,
                'fine_amount'   => 20000,
                'paid_at'       => null
            ],
            [
                'loan_id'       => 4,
                'amount_paid'   => 15000,
                'fine_amount'   => 15000,
                'paid_at'       => '2023-08-24 09:00:00'
            ],
        ];

        $this->db->table('fines')->insertBatch($data);
    }
}
