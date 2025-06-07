<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pembayaran_id' => ['type' => 'INT', 'auto_increment' => true],
            'pemesanan_id' => ['type' => 'INT', 'unsigned' => true],
            'metode' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'lunas'], 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('pembayaran_id', true);
        $this->forge->addForeignKey('pemesanan_id', 'pemesanan', 'pemesanan_id');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
    }
}
