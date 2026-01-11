<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLeaveSystem extends Migration
{
    public function up()
    {
        // 1. Create tb_holidays table
        $this->forge->addField([
            'holiday_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'holiday_date' => [
                'type' => 'DATE',
                'comment' => 'วันที่หยุดราชการ/วันหยุดโรงเรียน',
            ],
            'holiday_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'ชื่อวันหยุด',
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
        $this->forge->addKey('holiday_id', true);
        $this->forge->addUniqueKey('holiday_date');
        $attributes = ['ENGINE' => 'InnoDB', 'CHARACTER SET' => 'utf8', 'COLLATE' => 'utf8_unicode_ci'];
        $this->forge->createTable('tb_holidays', true, $attributes);

        // 2. Add leave_period to tb_leave_requests
        $fields = [
            'leave_period' => [
                'type'       => 'ENUM',
                'constraint' => ['full', 'morning', 'afternoon'],
                'default'    => 'full',
                'after'      => 'leave_total_days',
                'comment'    => 'ช่วงเวลาที่ลา: เต็มวัน, ครึ่งวันเช้า, ครึ่งวันบ่าย',
            ],
        ];
        $this->forge->addColumn('tb_leave_requests', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_leave_requests', 'leave_period');
        $this->forge->dropTable('tb_holidays');
    }
}
