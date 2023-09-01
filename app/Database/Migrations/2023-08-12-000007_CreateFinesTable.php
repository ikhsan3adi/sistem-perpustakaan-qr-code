<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'loan_id' => [
                'type'          => 'BIGINT',
                'constraint'    => 20,
                'unsigned'      => true,
                'null'          => true
            ],
            'amount_paid' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => true
            ],
            'fine_amount' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'paid_at' => [
                'type'           => 'DATETIME',
                'null'           => true
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        // loan id foreign key
        $this->forge->addForeignKey('loan_id', 'loans', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('fines', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('fines');
    }
}
