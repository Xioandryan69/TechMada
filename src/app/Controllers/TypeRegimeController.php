<?php

namespace App\Controllers;

use App\Models\TypeRegimeModel;
use CodeIgniter\RESTful\ResourceController;

class TypeRegimeController extends ResourceController
{
    protected $typeRegimeModel;

    public function __construct()
    {
        $this->typeRegimeModel =
            new TypeRegimeModel();
    }

    /**
     * Liste tous les types de régimes
     */
    public function index()
    {
        $data =
            $this->typeRegimeModel
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Afficher un type de régime
     */
    public function show($id = null)
    {
        $type =
            $this->typeRegimeModel
                ->find($id);

        if (!$type) {

            return $this->failNotFound(
                'Type de régime introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $type
        ]);
    }

    /**
     * Créer type de régime
     */
    public function create()
    {
        $data = [

            'nom' =>
                trim(
                    $this->request
                        ->getPost('nom')
                ),

            'pourcentage' =>
                $this->request
                    ->getPost('pourcentage')
        ];

        /**
         * Validation simple
         */
        if (empty($data['nom'])) {

            return $this->failValidationErrors(
                'Le nom est obligatoire'
            );
        }

        if (!is_numeric($data['pourcentage'])
            || $data['pourcentage'] < 0
            || $data['pourcentage'] > 100) {

            return $this->failValidationErrors(
                'Pourcentage invalide (0-100)'
            );
        }

        if (!$this->typeRegimeModel
                ->insert($data)) {

            return $this->failValidationErrors(
                $this->typeRegimeModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' =>
                'Type de régime créé'
        ]);
    }

    /**
     * Modifier type régime
     */
    public function update($id = null)
    {
        $type =
            $this->typeRegimeModel
                ->find($id);

        if (!$type) {

            return $this->failNotFound(
                'Type de régime inexistant'
            );
        }

        $data = [

            'nom' =>
                trim(
                    $this->request
                        ->getVar('nom')
                ),

            'pourcentage' =>
                $this->request
                    ->getVar('pourcentage')
        ];

        /**
         * Validation
         */
        if (!is_numeric($data['pourcentage'])
            || $data['pourcentage'] < 0
            || $data['pourcentage'] > 100) {

            return $this->failValidationErrors(
                'Pourcentage invalide (0-100)'
            );
        }

        $this->typeRegimeModel
            ->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' =>
                'Type de régime modifié'
        ]);
    }

    /**
     * Supprimer type régime
     */
    public function delete($id = null)
    {
        $type =
            $this->typeRegimeModel
                ->find($id);

        if (!$type) {

            return $this->failNotFound(
                'Type de régime inexistant'
            );
        }

        $this->typeRegimeModel
            ->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' =>
                'Type de régime supprimé'
        ]);
    }
}