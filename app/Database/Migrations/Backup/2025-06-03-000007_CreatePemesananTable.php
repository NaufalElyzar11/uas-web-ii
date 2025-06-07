<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemesananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pemesanan_id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'wisata_id' => ['type' => 'INT', 'unsigned' => true],
            'jumlah_orang' => ['type' => 'INT'],
            'total_harga' => ['type' => 'INT'],
            'tanggal_pemesanan' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('pemesanan_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id');
        $this->forge->addForeignKey('wisata_id', 'wisata', 'wisata_id');
        $this->forge->createTable('pemesanan');
    }

    public function down()
    {
        $this->forge->dropTable('pemesanan');
    }
}