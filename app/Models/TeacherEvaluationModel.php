<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherEvaluationModel extends Model
{
    protected $table      = 'tb_teacher_evaluation';
    protected $primaryKey = 'eva_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'eva_teacher_id', 
        'eva_year', 
        'eva_round', 
        'eva_file', 
        'eva_canva_link', 
        'eva_status', 
        'eva_comment'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'eva_created_at';
    protected $updatedField  = 'eva_updated_at';
}
