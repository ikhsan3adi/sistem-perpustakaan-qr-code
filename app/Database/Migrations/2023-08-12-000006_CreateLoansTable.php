<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'BIGINT',
                'constraint'    => 20,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'book_id' => [
                'type'          => 'BIGINT',
                'constraint'    => 20,
                'unsigned'      => true,
            ],
            'quantity' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'default'       => 1
            ],
            'member_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
            ],
            'unique_code' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'loan_date' => [
                'type'          => 'DATE',
            ],
            'due_date' => [
                'type'          => 'DATE',
            ],
            'return_date' => [
                'type'          => 'DATE',
                'null'          => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        // primary key
        $this->forge->addPrimaryKey('id');

        // book id foreign key
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'NO ACTION');

        // member id foreign key
        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('loans');
    }

    public function down()
    {
        $this->forge->dropTable('loans');
    }
}
