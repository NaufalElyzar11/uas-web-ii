<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserMinatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'minat_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey(['user_id', 'minat_id'], true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('minat_id', 'minat', 'minat_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_minat');
    }

    public function down()
    {
        $this->forge->dropTable('user_minat');
    }
}
