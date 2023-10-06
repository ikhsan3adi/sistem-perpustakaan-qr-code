<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create(locale: 'id_ID');

        $authorityType = ['p', 'o', 'c'];

        for ($i = 0; $i < 5; $i++) {
            $data = [
                'name' => $faker->name,
                'year' => $faker->year,
                'authority_type' => $authorityType[$faker->numberBetween(0, 2)],
                'auth_list' => null
            ];

            $this->db->table('authors')->insert($data);
        }
    }
}
