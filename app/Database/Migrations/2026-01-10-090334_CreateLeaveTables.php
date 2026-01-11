<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveTables extends Migration
{
    public function up()
    {
        // 1. ตารางประเภทการลา (Leave Types)
        $this->forge->addField([
            'leave_type_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'leave_type_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'ชื่อประเภทการลา เช่น ลาป่วย, ลากิจ',
            ],
            'leave_type_quota' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
                'comment'    => 'จำนวนวันที่ลาได้สูงสุดต่อปีการศึกษา',
            ],
            'leave_type_status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'comment'    => 'สถานะการใช้งาน',
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
        $this->forge->addKey('leave_type_id', true);
        $attributes = ['ENGINE' => 'InnoDB', 'CHARACTER SET' => 'utf8', 'COLLATE' => 'utf8_unicode_ci'];
        $this->forge->createTable('tb_leave_types', true, $attributes);

        // 2. ตารางรายการขอลา (Leave Requests)
        $this->forge->addField([
            'leave_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pers_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'รหัสบุคลากรที่ขอลา',
            ],
            'leave_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'รหัสประเภทการลา',
            ],
            'leave_topic' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'หัวข้อการลา หรือเหตุผลสั้นๆ',
            ],
            'leave_detail' => [
                'type' => 'TEXT',
                'null' => true,
                'comment'    => 'รายละเอียดเพิ่มเติม',
            ],
            'leave_start_date' => [
                'type' => 'DATE',
                'comment'    => 'วันที่เริ่มลา',
            ],
            'leave_end_date' => [
                'type' => 'DATE',
                'comment'    => 'วันที่สิ้นสุดการลา',
            ],
            'leave_total_days' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'comment'    => 'รวมจำนวนวันที่ลา (รองรับจุดทศนิยมกรณีลาครึ่งวัน)',
            ],
            'leave_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'แนบไฟล์ เช่น ใบรับรองแพทย์',
            ],
            'leave_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'cancelled'],
                'default'    => 'pending',
                'comment'    => 'สถานะการลา',
            ],
            'leave_comment' => [
                'type' => 'TEXT',
                'null' => true,
                'comment'    => 'ความคิดเห็นจากผู้อนุมัติ',
            ],
            'approved_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'รหัสบุคลากรที่ผู้อนุมัติ',
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('leave_id', true);
        $this->forge->addForeignKey('leave_type_id', 'tb_leave_types', 'leave_type_id', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB', 'CHARACTER SET' => 'utf8', 'COLLATE' => 'utf8_unicode_ci'];
        $this->forge->createTable('tb_leave_requests', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('tb_leave_requests');
        $this->forge->dropTable('tb_leave_types');
    }
}
