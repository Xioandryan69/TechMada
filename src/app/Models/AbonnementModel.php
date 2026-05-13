<?php

namespace App\Models;

use CodeIgniter\Model;

class AbonnementModel extends Model
{
    protected $table = 'abonnements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'type',
        'date_debut',
        'date_fin'
    ];

    public function getActifParUser(int $userId): ?array
    {
        $today = date('Y-m-d');

        return $this->where('user_id', $userId)
            ->groupStart()
                ->where('date_fin IS NULL', null, false)
                ->orWhere('date_fin >=', $today)
            ->groupEnd()
            ->orderBy('date_debut', 'DESC')
            ->first();
    }

    public function activerGold(int $userId, int $dureeJours = 365): bool
    {
        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime("+{$dureeJours} days"));

        $actif = $this->getActifParUser($userId);

        if ($actif) {
            return (bool) $this->update($actif['id'], [
                'type' => 'GOLD',
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
            ]);
        }

        return $this->insert([
            'user_id' => $userId,
            'type' => 'GOLD',
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]) !== false;
    }
}