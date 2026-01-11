<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // ดึง pers_id ตัวอย่างที่มีอยู่จริงในระบบ
        $person = $db->table('tb_personnel')->select('pers_id')->limit(2)->get()->getResultArray();
        
        if (empty($person)) {
            return;
        }

        $data = [
            [
                'pers_id'           => $person[0]['pers_id'],
                'leave_type_id'     => 1, // ลาป่วย (อ้างอิงจาก Seeder ที่แล้ว)
                'leave_topic'       => 'เป็นไข้หวัดตัวร้อน',
                'leave_detail'      => 'มีอาการไข้สูงและไอต่อเนื่อง จึงขอลาหยุดเพื่อพักผ่อนและไปพบแพทย์',
                'leave_start_date'  => date('Y-m-d'),
                'leave_end_date'    => date('Y-m-d', strtotime('+1 day')),
                'leave_total_days'  => 2,
                'leave_status'      => 'pending',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'pers_id'           => isset($person[1]) ? $person[1]['pers_id'] : $person[0]['pers_id'],
                'leave_type_id'     => 2, // ลากิจส่วนตัว
                'leave_topic'       => 'ไปทำธุระครอบครัวที่ต่างจังหวัด',
                'leave_detail'      => 'มีความจำเป็นต้องเดินทางไปจัดการธุระสำคัญของครอบครัว',
                'leave_start_date'  => date('Y-m-d', strtotime('+2 days')),
                'leave_end_date'    => date('Y-m-d', strtotime('+2 days')),
                'leave_total_days'  => 1,
                'leave_status'      => 'pending',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $db->table('tb_leave_requests')->insertBatch($data);
    }
}
