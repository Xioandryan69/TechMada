<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'code_id',
        'montant',
        'date_transaction'
    ];
        /**
     * Retourne toutes les transactions d'un utilisateur
     */
    public function parUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
    }
 
    /**
     * Total des montants débités pour un utilisateur
     */
    public function totalParUser(int $userId): float
    {
        $result = $this->selectSum('montant')
            ->where('user_id', $userId)
            ->first();
 
        return (float) ($result['montant'] ?? 0);
    }

}