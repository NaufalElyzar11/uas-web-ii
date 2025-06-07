<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'username' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'daerah' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'jenis_kelamin' => ['type' => 'ENUM', 'constraint' => ['L', 'P']],
            'umur' => ['type' => 'INT', 'constraint' => 3],
            'role' => ['type' => 'ENUM', 'constraint' => ['user', 'admin'], 'default' => 'user'],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}