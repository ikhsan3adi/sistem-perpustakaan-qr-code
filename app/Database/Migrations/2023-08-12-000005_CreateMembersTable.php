<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'uid' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'first_name' => [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
            ],
            'last_name' => [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
                'null'          => true,
            ],
            'email' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'phone' => [
                'type'          => 'VARCHAR',
                'constraint'    => 20,
            ],
            'address' => [
                'type'          => 'TEXT',
                'null'          => true,
            ],
            'date_of_birth' => [
                'type'          => 'DATE',
                'null'          => true,
            ],
            'gender' => [
                'type'          => 'ENUM',
                'constraint'    => ['Male', 'Female'],
            ],
            'qr_code' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
                'null'          => true
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addUniqueKey('uid');

        $this->forge->createTable('members', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('members');
    }
}
