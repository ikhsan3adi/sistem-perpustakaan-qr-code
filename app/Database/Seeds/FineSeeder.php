<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FineSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nominal' => 5000,
        ];

        $this->db->table('fines')->insert($data);
    }
}
