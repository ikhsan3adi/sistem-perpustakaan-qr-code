<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRacksTable extends Migration
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
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 8,
            ],
            'floor' => [
                'type'           => 'VARCHAR',
                'constraint'     => 16
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        // primary key
        $this->forge->addKey('id', primary: TRUE);

        $this->forge->createTable('racks', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('racks');
    }
}
