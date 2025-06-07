<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWisataTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'wisata_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'lokasi' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'harga' => ['type' => 'INT'],
            'deskripsi' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('wisata_id', true);
        $this->forge->createTable('wisata');
    }

    public function down()
    {
        $this->forge->dropTable('wisata');
    }
}