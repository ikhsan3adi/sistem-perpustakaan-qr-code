<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RackSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => '1A',
                'floor' => '1',
            ],
            [
                'name' => '1B',
                'floor' => '1',
            ],
            [
                'name' => '1C',
                'floor' => '1',
            ],
            [
                'name' => '2A',
                'floor' => '2',
            ],
            [
                'name' => '2B',
                'floor' => '2',
            ],
            [
                'name' => '2C',
                'floor' => '2',
            ],
            [
                'name' => '3A',
                'floor' => '3',
            ],
            [
                'name' => '3B',
                'floor' => '3',
            ],
            [
                'name' => '3C',
                'floor' => '3',
            ],
            [
                'name' => '3D',
                'floor' => '3',
            ],
        ];

        $this->db->table('racks')->insertBatch($data);
    }
}
