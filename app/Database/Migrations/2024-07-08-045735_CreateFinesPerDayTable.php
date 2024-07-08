<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinesPerDayTable extends Migration
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
            'amount' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('fines_per_day', TRUE);

        $this->db->table('fines_per_day')->insert([
            'amount' => 1000
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('fines_per_day');
    }
}
