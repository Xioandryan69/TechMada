<?php

namespace App\Controllers;

use App\Models\RegimeUserModel;
use CodeIgniter\RESTful\ResourceController;

class RegimeUserController extends ResourceController
{
    protected $regimeUserModel;

    public function __construct()
    {
        $this->regimeUserModel =
            new RegimeUserModel();
    }

    /**
     * Liste toutes les associations user-régime
     */
    public function index()
    {
        $data =
            $this->regimeUserModel
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Afficher une relation
     */
    public function show($id = null)
    {
        $relation =
            $this->regimeUserModel
                ->find($id);

        if (!$relation) {

            return $this->failNotFound(
                'Relation introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $relation
        ]);
    }

    /**
     * Assigner un régime à un utilisateur
     */
    public function create()
    {
        $data = [

            'id_user' =>
                $this->request
                    ->getPost('id_user'),

            'type_regime_id' =>
                $this->request
                    ->getPost('type_regime_id')
        ];

        if (empty($data['id_user'])
            || empty($data['type_regime_id'])) {

            return $this->failValidationErrors(
                'id_user et type_regime_id obligatoires'
            );
        }

        /**
         * éviter doublon simple
         */
        $exists =
            $this->regimeUserModel
                ->where(
                    'id_user',
                    $data['id_user']
                )
                ->where(
                    'type_regime_id',
                    $data['type_regime_id']
                )
                ->first();

        if ($exists) {

            return $this->failValidationErrors(
                'Ce régime est déjà assigné à cet utilisateur'
            );
        }

        if (!$this->regimeUserModel
                ->insert($data)) {

            return $this->failValidationErrors(
                $this->regimeUserModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' =>
                'Régime assigné à l’utilisateur'
        ]);
    }

    /**
     * Modifier association
     */
    public function update($id = null)
    {
        $relation =
            $this->regimeUserModel
                ->find($id);

        if (!$relation) {

            return $this->failNotFound(
                'Relation inexistante'
            );
        }

        $data = [

            'id_user' =>
                $this->request
                    ->getVar('id_user'),

            'type_regime_id' =>
                $this->request
                    ->getVar('type_regime_id')
        ];

        $this->regimeUserModel
            ->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' =>
                'Relation modifiée'
        ]);
    }

    /**
     * Supprimer relation
     */
    public function delete($id = null)
    {
        $relation =
            $this->regimeUserModel
                ->find($id);

        if (!$relation) {

            return $this->failNotFound(
                'Relation inexistante'
            );
        }

        $this->regimeUserModel
            ->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' =>
                'Relation supprimée'
        ]);
    }

    /**
     * Régimes d’un utilisateur
     */
    public function parUser($id_user)
    {
        $data =
            $this->regimeUserModel
                ->where('id_user', $id_user)
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }
}