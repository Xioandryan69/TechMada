<?php

namespace App\Models;

use CodeIgniter\Model;

class RecommendationWeightModel extends Model
{
    protected $table = 'recommendations_weight';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'age_range_id',
        'genre',
        'imc_min',
        'imc_max',
        'poids_min',
        'poids_max'
    ];
}