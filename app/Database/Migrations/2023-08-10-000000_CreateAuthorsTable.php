<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthorTable extends Migration
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
                'constraint'     => 255,
            ],
            'year' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'authority_type' => [
                'type'           => 'ENUM',
                'constraint'     => ['p', 'o', 'c'],
                'default'        => 'p',
            ],
            'auth_list' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('authors', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('authors');
    }
}
