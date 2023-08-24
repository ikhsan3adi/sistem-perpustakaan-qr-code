<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder as DatabaseSeeder;

class Seeder extends DatabaseSeeder
{
    public function run()
    {
        // run category, rack, book & bookstock seeder
        $this->call('BookSeeder');

        // run member seeder
        $this->call('MemberSeeder');

        // run loan & fine seeder
        $this->call('LoanSeeder');
    }
}
