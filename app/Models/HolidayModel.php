<?php

namespace App\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table            = 'tb_holidays';
    protected $primaryKey       = 'holiday_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['holiday_date', 'holiday_name', 'created_at', 'updated_at'];

    // Dates for carbon/time interaction
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function isHoliday($date)
    {
        return $this->where('holiday_date', $date)->first() !== null;
    }

    public function getHolidaysInRange($startDate, $endDate)
    {
        return $this->where('holiday_date >=', $startDate)
                    ->where('holiday_date <=', $endDate)
                    ->findAll();
    }
}
