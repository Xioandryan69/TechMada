<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table            = 'types_conge';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'libelle', 
        'jours_annuels', 
        'deductible'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'libelle'      => 'required|min_length[3]|max_length[100]',
        'jours_annuels'=> 'required|integer|greater_than[0]',
        'deductible'   => 'required|in_list[0,1]'
    ];
}