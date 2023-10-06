<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PlaceSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create(locale: 'id_ID');

        for ($i = 0; $i < 5; $i++) {
            $data = [
                'name' => $faker->city()
            ];

            $this->db->table('places')->insert($data);
        }
    }
}
