<?php

namespace App\Models;

use CodeIgniter\Model;

class ImcCategoryModel extends Model
{
    protected $table = 'imc_categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'min_value',
        'max_value',
        'label'
    ];
}