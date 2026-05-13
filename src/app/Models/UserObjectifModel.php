<?php

namespace App\Models;

use CodeIgniter\Model;

class UserObjectifModel extends Model
{
    protected $table = 'user_objectifs';
    protected $primaryKey = null;

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'objectif_id'
    ];

    public function sauvegarder(int $userId, int $objectifId): bool
    {
        $existant = $this->where('user_id', $userId)->first();

        if ($existant) {
            return (bool) $this->builder()
                ->where('user_id', $userId)
                ->update([
                    'objectif_id' => $objectifId,
                ]);
        }

        return $this->insert([
            'user_id' => $userId,
            'objectif_id' => $objectifId,
        ]) !== false;
    }
}