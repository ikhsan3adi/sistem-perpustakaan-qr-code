<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Fiksi',
            ],
            [
                'name' => 'Non-Fiksi',
            ],
            [
                'name' => 'Sejarah',
            ],
            [
                'name' => 'Komik',
            ],
            [
                'name' => 'Teknologi',
            ]
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
