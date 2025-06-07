<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatistikKunjunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'statistik_id' => ['type' => 'INT', 'auto_increment' => true],
            'wisata_id' => ['type' => 'INT', 'unsigned' => true],
            'tanggal' => ['type' => 'DATE'],
            'jumlah_pengunjung' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('statistik_id', true);
        $this->forge->addForeignKey('wisata_id', 'wisata', 'wisata_id');
        $this->forge->createTable('statistik_kunjungan');
    }

    public function down()
    {
        $this->forge->dropTable('statistik_kunjungan');
    }
}

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWishlistTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'wisata_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey(['user_id', 'wisata_id'], true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id');
        $this->forge->addForeignKey('wisata_id', 'wisata', 'wisata_id');
        $this->forge->createTable('wishlist');
    }

    public function down()
    {
        $this->forge->dropTable('wishlist');
    }
}