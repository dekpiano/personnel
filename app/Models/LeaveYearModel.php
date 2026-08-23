<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveYearModel extends Model
{
    protected $table            = 'tb_leave_years';
    protected $primaryKey       = 'ly_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['ly_name', 'ly_start_date', 'ly_end_date', 'ly_status', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveYear()
    {
        return $this->where('ly_status', 'active')->first();
    }

    /**
     * คำนวณและดึงข้อมูลปีงบประมาณอัตโนมัติจากวันที่ที่ระบุ
     * ปีงบประมาณไทย: 1 ตุลาคม - 30 กันยายน ของปีถัดไป
     * ตัวอย่าง: 23/08/2026 -> ปีงบประมาณ 2569 (1 ต.ค. 2025 - 30 ก.ย. 2026)
     */
    public function getFiscalYearInfo($dateStr = null): array
    {
        $time = $dateStr ? strtotime($dateStr) : time();
        $month = (int)date('n', $time);
        $year = (int)date('Y', $time);

        if ($month >= 10) {
            $fiscalYearAD = $year + 1;
            $startAD = $year . '-10-01';
            $endAD = ($year + 1) . '-09-30';
        } else {
            $fiscalYearAD = $year;
            $startAD = ($year - 1) . '-10-01';
            $endAD = $year . '-09-30';
        }

        $fiscalYearBE = $fiscalYearAD + 543;

        // ปีงบประมาณก่อนหน้า (ย้อนหลัง 1 ปี สำหรับคำนวณรวม 2 ปีงบ)
        $prevFiscalYearAD = $fiscalYearAD - 1;
        $prevStartAD = ($fiscalYearAD - 2) . '-10-01';
        $twoYearStartAD = $prevStartAD; // จุดเริ่มต้นของ 2 ปีงบประมาณ
        $twoYearEndAD = $endAD;         // จุดสิ้นสุดของ 2 ปีงบประมาณ

        return [
            'fiscal_year_be'       => $fiscalYearBE,
            'fiscal_year_name'     => "ปีงบประมาณ {$fiscalYearBE}",
            'start_date'           => $startAD,
            'end_date'             => $endAD,
            'yearly_max_days'      => 23.0, // สิทธิ์ลาสูงสุดต่อ 1 ปีงบประมาณ = 23 วัน
            // ข้อมูลรอบ 2 ปีงบประมาณ (รวม 45 วัน)
            'two_year_start'       => $twoYearStartAD,
            'two_year_end'         => $twoYearEndAD,
            'two_year_max_days'    => 45.0, // สิทธิ์ลาสูงสุดรวม 2 ปีงบประมาณ = 45 วัน
            'prev_fiscal_year_be'  => $fiscalYearBE - 1
        ];
    }
}

