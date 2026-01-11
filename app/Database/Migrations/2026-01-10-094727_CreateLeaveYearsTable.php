<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveYearsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ly_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ly_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'ชื่อปีการศึกษา เช่น 2568',
            ],
            'ly_start_date' => [
                'type' => 'DATE',
                'comment' => 'วันที่เริ่มนับโควตา',
            ],
            'ly_end_date' => [
                'type' => 'DATE',
                'comment' => 'วันที่สิ้นสุดการนับโควตา',
            ],
            'ly_status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'inactive',
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
        $this->forge->addKey('ly_id', true);
        $attributes = ['ENGINE' => 'InnoDB', 'CHARACTER SET' => 'utf8', 'COLLATE' => 'utf8_unicode_ci'];
        $this->forge->createTable('tb_leave_years', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tb_leave_years');
    }
}
