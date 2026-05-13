<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table = 'wallet';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'solde'
    ];

    public function getOuCreerParUser(int $userId): array
    {
        $wallet = $this->where('user_id', $userId)->first();

        if ($wallet) {
            return $wallet;
        }

        $walletId = $this->insert([
            'user_id' => $userId,
            'solde' => 0,
        ]);

        return [
            'id' => $walletId,
            'user_id' => $userId,
            'solde' => 0,
        ];
    }

    public function crediter(int $userId, float $montant): bool
    {
        $wallet = $this->getOuCreerParUser($userId);

        return (bool) $this->update($wallet['id'], [
            'solde' => (float) $wallet['solde'] + $montant,
        ]);
    }

    public function debiter(int $userId, float $montant): bool
    {
        $wallet = $this->getOuCreerParUser($userId);

        if ((float) $wallet['solde'] < $montant) {
            return false;
        }

        return (bool) $this->update($wallet['id'], [
            'solde' => (float) $wallet['solde'] - $montant,
        ]);
    }
}