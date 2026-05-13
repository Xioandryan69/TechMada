<?php

namespace App\Models;

use CodeIgniter\Model;

class AgeRangeModel extends Model
{
    protected $table = 'age_ranges';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'age_min',
        'age_max'
    ];
}