<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBoardRowsTable extends Migration
{
    public function up()
    {
        // 1. Create tb_board_rows
        $this->forge->addField([
            'row_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'row_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'row_cols' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1, // Number of columns in this row (1-6)
            ],
            'row_sort' => [
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
        $this->forge->addKey('row_id', true);
        $this->forge->createTable('tb_board_rows');

        // 2. Add row_id to tb_board
        $this->forge->addColumn('tb_board', [
            'row_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'board_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('tb_board_rows');
        $this->forge->dropColumn('tb_board', 'row_id');
    }
}
