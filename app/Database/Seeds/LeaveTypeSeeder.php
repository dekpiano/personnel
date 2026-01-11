<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'leave_type_name'  => 'ลาป่วย',
                'leave_type_quota' => 30,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'leave_type_name'  => 'ลากิจส่วนตัว',
                'leave_type_quota' => 15,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'leave_type_name'  => 'ลาพักผ่อน',
                'leave_type_quota' => 10,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'leave_type_name'  => 'ลาคลอดบุตร',
                'leave_type_quota' => 90,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'leave_type_name'  => 'ลาไปช่วยเหลือภริยาที่คลอดบุตร',
                'leave_type_quota' => 15,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'leave_type_name'  => 'ลาอุปสมบท หรือลาไปประกอบพิธีฮัจย์',
                'leave_type_quota' => 120,
                'leave_type_status' => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('tb_leave_types')->insertBatch($data);
    }
}
