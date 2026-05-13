<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'nom', 'prenom', 'email', 'password', 'genre', 
        'date_naissance', 'role'
    ];

    public function getProfileSummary(int $userId): ?array
    {
        return $this->db
            ->table('users u')
            ->select('
                u.id,
                u.nom,
                u.prenom,
                u.email,
                u.genre,
                u.date_naissance,
                u.role,
                uh.taille,
                uh.poids,
                uh.imc,
                uh.date_mesure,
                o.id AS objectif_id,
                o.nom AS objectif_nom,
                w.solde
            ')
            ->join('user_health uh', 'uh.user_id = u.id', 'left')
            ->join('user_objectifs uo', 'uo.user_id = u.id', 'left')
            ->join('objectifs o', 'o.id = uo.objectif_id', 'left')
            ->join('wallet w', 'w.user_id = u.id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();
    }
}