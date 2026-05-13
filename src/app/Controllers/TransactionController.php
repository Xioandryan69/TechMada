<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;

class TransactionController extends ResourceController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel =
            new TransactionModel();
    }

    /**
     * Liste toutes les transactions
     */
    public function index()
    {
        $data =
            $this->transactionModel
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Afficher une transaction
     */
    public function show($id = null)
    {
        $transaction =
            $this->transactionModel
                ->find($id);

        if (!$transaction) {

            return $this->failNotFound(
                'Transaction introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Créer transaction
     */
    public function create()
    {
        $data = [

            'user_id' =>
                $this->request
                    ->getPost('user_id'),

            'code_id' =>
                $this->request
                    ->getPost('code_id'),

            'montant' =>
                $this->request
                    ->getPost('montant'),

            'date_transaction' =>
                date('Y-m-d H:i:s')
        ];

        if (empty($data['user_id'])
            || empty($data['montant'])) {

            return $this->failValidationErrors(
                'user_id et montant obligatoires'
            );
        }

        if (!is_numeric($data['montant'])
            || $data['montant'] <= 0) {

            return $this->failValidationErrors(
                'Montant invalide'
            );
        }

        if (!$this->transactionModel
                ->insert($data)) {

            return $this->failValidationErrors(
                $this->transactionModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' =>
                'Transaction enregistrée'
        ]);
    }

    /**
     * Modifier transaction
     */
    public function update($id = null)
    {
        $transaction =
            $this->transactionModel
                ->find($id);

        if (!$transaction) {

            return $this->failNotFound(
                'Transaction inexistante'
            );
        }

        $data = [

            'user_id' =>
                $this->request
                    ->getVar('user_id'),

            'code_id' =>
                $this->request
                    ->getVar('code_id'),

            'montant' =>
                $this->request
                    ->getVar('montant')
        ];

        $this->transactionModel
            ->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' =>
                'Transaction modifiée'
        ]);
    }

    /**
     * Supprimer transaction
     */
    public function delete($id = null)
    {
        $transaction =
            $this->transactionModel
                ->find($id);

        if (!$transaction) {

            return $this->failNotFound(
                'Transaction inexistante'
            );
        }

        $this->transactionModel
            ->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' =>
                'Transaction supprimée'
        ]);
    }

    /**
     * Transactions par utilisateur
     */
    public function parUser($user_id)
    {
        $data =
            $this->transactionModel
                ->where('user_id', $user_id)
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Total dépensé par utilisateur
     */
    public function totalUser($user_id)
    {
        $total =
            $this->transactionModel
                ->selectSum('montant')
                ->where('user_id', $user_id)
                ->first();

        return $this->respond([
            'success' => true,
            'user_id' => $user_id,
            'total_depense' =>
                $total['montant'] ?? 0
        ]);
    }
}