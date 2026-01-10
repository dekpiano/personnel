<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: สร้างตาราง tb_personnel_documents สำหรับเก็บเอกสารบุคลากร
 * ใช้กับระบบ กพ.7 (ประวัติบุคลากร)
 */
class CreatePersonnelDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสเอกสาร (Primary Key)',
            ],
            'pers_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'รหัสบุคลากร (FK to tb_personnel)',
            ],
            'doc_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'หมวดหมู่เอกสาร: personal, license, education, work_order, training, decoration, other',
            ],
            'doc_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'ประเภทเอกสารย่อย: id_card, house_reg, teacher_license, etc.',
            ],
            'doc_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'ชื่อเอกสาร/คำอธิบาย',
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'ชื่อไฟล์ต้นฉบับ',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'comment'    => 'ที่อยู่ไฟล์ในระบบ',
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'นามสกุลไฟล์: pdf, jpg, png',
            ],
            'file_size' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'ขนาดไฟล์ (bytes)',
            ],
            'related_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'รหัสอ้างอิง (เช่น education_id, training_id)',
            ],
            'doc_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'วันที่เอกสาร (ถ้ามี)',
            ],
            'doc_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'เลขที่อ้างอิง เช่น เลขที่คำสั่ง',
            ],
            'doc_note' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'หมายเหตุ',
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ผู้อัปโหลด (pers_id)',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'วันที่สร้าง',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'วันที่แก้ไขล่าสุด',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('pers_id');
        $this->forge->addKey(['doc_category', 'doc_type']);
        $this->forge->addKey('related_id');
        
        $this->forge->createTable('tb_personnel_documents', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_personnel_documents', true);
    }
}
