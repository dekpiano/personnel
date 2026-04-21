<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBoardTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'board_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'board_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'board_firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'board_lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'board_position' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'board_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'board_img' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'board_sort' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('board_id', true);
        $this->forge->createTable('tb_board');
    }

    public function down()
    {
        $this->forge->dropTable('tb_board');
    }
}
