<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMinatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'minat_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
        ]);
        $this->forge->addKey('minat_id', true);
        $this->forge->createTable('minat');
    }

    public function down()
    {
        $this->forge->dropTable('minat');
    }
}