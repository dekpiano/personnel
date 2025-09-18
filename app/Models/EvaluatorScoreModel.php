<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluatorScoreModel extends Model
{
    protected $DBGroup    = 'pa_evaluation';
    protected $table      = 'tb_evaluator_scores';
    protected $primaryKey = 'es_id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'es_id', 'ev_id', 'e_id', 'es_part1_score', 'es_part2_score', 
        'es_total_score', 'es_strong_points', 'es_areas_for_improvement', 'es_comments'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
