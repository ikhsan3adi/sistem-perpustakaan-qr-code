<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use Tests\Support\Models\BookFabricator;

class BookSeeder extends Seeder
{
    public function run()
    {
        // populate other table first, avoid foreign key constraint fail
        // isi data tabel lainnya dahulu, menghindari kegagalan fk contstraint
        $this->call('AuthorSeeder');
        $this->call('PublisherSeeder');
        $this->call('PlaceSeeder');

        $fabricator = new Fabricator(BookFabricator::class, locale: 'id_ID');
        // insert book data
        $fabricator->create(30);

        $this->call('BookStockSeeder');
    }
}
