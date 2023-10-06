<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PublisherSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create(locale: 'id_ID');

        for ($i = 0; $i < 5; $i++) {
            $data = [
                'name' => $faker->company()
            ];

            $this->db->table('publishers')->insert($data);
        }
    }
}
