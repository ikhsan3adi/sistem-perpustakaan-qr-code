<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookStockTable extends Migration
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
            'book_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
            ],
            'quantity' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        // primary key
        $this->forge->addKey('id', primary: TRUE);

        // book id foreign key
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('book_stock', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('book_stock');
    }
}
