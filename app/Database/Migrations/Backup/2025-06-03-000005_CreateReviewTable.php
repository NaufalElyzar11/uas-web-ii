<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'review_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'wisata_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rating' => ['type' => 'INT', 'constraint' => 1],
            'komentar' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('review_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id');
        $this->forge->addForeignKey('wisata_id', 'wisata', 'wisata_id');
        $this->forge->createTable('review');
    }

    public function down()
    {
        $this->forge->dropTable('review');
    }
}