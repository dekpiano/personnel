<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemScoreModel extends Model
{
    protected $DBGroup    = 'pa_evaluation';
    protected $table      = 'tb_item_scores';
    protected $primaryKey = 'is_id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['is_id', 'es_id', 'ri_id', 'is_score', 'is_notes','is_calculated_score'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
