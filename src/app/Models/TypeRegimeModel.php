<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeRegimeModel extends Model
{
    protected $table = 'type_regimes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'pourcentage'
    ];
}