<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBooksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => 127
            ],
            'author' => [
                'type'           => 'VARCHAR',
                'constraint'     => 64
            ],
            'publisher' => [
                'type'           => 'VARCHAR',
                'constraint'     => 64
            ],
            'isbn' => [
                'type'           => 'VARCHAR',
                'constraint'     => 13
            ],
            'year' => [
                'type'           => 'YEAR',
                'constraint'     => 4
            ],
            'rack_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'category_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'book_cover' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addUniqueKey('slug');

        // rack id foreign key
        $this->forge->addForeignKey('rack_id', 'racks', 'id', 'CASCADE', 'NO ACTION');

        // category id foreign key
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('books', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('books');
    }
}
