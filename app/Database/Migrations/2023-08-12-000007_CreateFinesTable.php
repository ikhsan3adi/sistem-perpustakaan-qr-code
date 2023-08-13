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
            'nominal' => [
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

        $this->forge->createTable('fines', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('fines');
    }
}
