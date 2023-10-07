<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBooksForeignKey extends Migration
{
    public function up()
    {
        // tidak jadi diimplementasikan karena data memiliki nilai null di kolom berikut

        // author id foreign key
        // $this->forge->addForeignKey('author_id', 'authors', 'id', 'CASCADE', 'NO ACTION', 'fk_book_author');

        // publisher id foreign key
        // $this->forge->addForeignKey('publisher_id', 'publishers', 'id', 'CASCADE', 'NO ACTION', 'fk_book_publisher');

        // place id foreign key
        // $this->forge->addForeignKey('place_id', 'places', 'id', 'CASCADE', 'NO ACTION', 'fk_book_place');

        // $this->forge->processIndexes('books');
    }

    public function down()
    {
        // $this->forge->dropForeignKey('books', 'fk_book_author');
        // $this->forge->dropForeignKey('books', 'fk_book_publisher');
        // $this->forge->dropForeignKey('books', 'fk_book_place');
    }
}
