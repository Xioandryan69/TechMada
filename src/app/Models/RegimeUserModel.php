<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeUserModel extends Model
{
    protected $table = 'regime_user';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_user',
        'type_regime_id'
    ];
}