<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveRequestModel extends Model
{
    protected $table            = 'tb_leave_requests';
    protected $primaryKey       = 'leave_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pers_id', 
        'leave_type_id', 
        'leave_topic', 
        'leave_detail', 
        'leave_start_date', 
        'leave_end_date', 
        'leave_total_days', 
        'leave_period',
        'leave_file', 
        'leave_status', 
        'leave_comment', 
        'approved_by', 
        'approved_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * ดึงข้อมูลการลาพร้อมข้อมูลบุคลากรและประเภทการลา
     */
    public function getLeaveDetails($status = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_leave_requests.*, tb_leave_types.leave_type_name, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_img');
        $builder->join('tb_leave_types', 'tb_leave_types.leave_type_id = tb_leave_requests.leave_type_id');
        $builder->join('tb_personnel', 'tb_personnel.pers_id = tb_leave_requests.pers_id');
        
        if ($status) {
            $builder->where('leave_status', $status);
        }
        
        $builder->orderBy('tb_leave_requests.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * ดึงสถิติจำนวนวันที่ลาไปแล้วของบุคลากรรายบุคคล แยกตามประเภทการลา
     * เฉพาะรายการที่ได้รับการอนุมัติแล้ว และอยู่ในช่วงปีการศึกษาที่กำหนด (ถ้ามี)
     */
    public function getLeaveStats($pers_id, $leave_type_id, $startDate = null, $endDate = null)
    {
        $builder = $this->where('pers_id', $pers_id)
                        ->where('leave_type_id', $leave_type_id)
                        ->where('leave_status', 'approved');

        if ($startDate && $endDate) {
            $builder->where('leave_start_date >=', $startDate)
                    ->where('leave_start_date <=', $endDate);
        }

        return $builder->selectSum('leave_total_days')
                       ->get()
                       ->getRowArray()['leave_total_days'] ?? 0;
    }
}
