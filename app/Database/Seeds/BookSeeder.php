<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use Tests\Support\Models\BookFabricator;

class BookSeeder extends Seeder
{
    public function run()
    {
        // populate racks & categories table first, avoid foreign key constraint fail
        // isi data tabel rak dan kategori dahulu, menghindari kegagalan fk contstraint
        $this->call('RackSeeder');
        $this->call('CategorySeeder');

        $fabricator = new Fabricator(BookFabricator::class, locale: 'id_ID');
        // insert book data
        $fabricator->create(30);

        $this->call('BookStockSeeder');
    }
}
