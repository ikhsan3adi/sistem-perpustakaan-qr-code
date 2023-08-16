<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use Tests\Support\Models\MemberFabricator;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $fabricator = new Fabricator(MemberFabricator::class, locale: 'id_ID');
        // insert member data
        $fabricator->create(5);
    }
}
