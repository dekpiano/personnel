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
}
