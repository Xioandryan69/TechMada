<?php

namespace App\Models;

use CodeIgniter\Model;

class HealthRuleModel extends Model
{
    protected $table = 'health_rules';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'type',
        'age_min',
        'age_max',
        'genre',
        'valeur_min',
        'valeur_max'
    ];
}