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
            'edition' => [
                'type'           => 'VARCHAR',
                'constraint'     => 127,
                'null'           => true,
            ],
            'isbn' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'year' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'collation' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'call_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'language_id' => [
                'type'           => 'VARCHAR',
                'constraint'     => '5',
                'default'        => 'en'
            ],
            'source' => [
                'type'           => 'VARCHAR',
                'constraint'     => '3',
                'null'           => true,
            ],
            'book_cover' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'file_att' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'author_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true
            ],
            'publisher_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true
            ],
            'place_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addUniqueKey('slug');

        $this->forge->createTable('books', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('books');
    }
}
