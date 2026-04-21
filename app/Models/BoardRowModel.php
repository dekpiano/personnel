<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardRowModel extends Model
{
    protected $table            = 'tb_board_rows';
    protected $primaryKey       = 'row_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'row_title',
        'row_cols',
        'row_sort',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
