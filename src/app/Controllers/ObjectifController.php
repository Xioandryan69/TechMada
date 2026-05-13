<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use CodeIgniter\RESTful\ResourceController;

class ObjectifController extends ResourceController
{
    protected $objectifModel;

    public function __construct()
    {
        $this->objectifModel = new ObjectifModel();
    }

    /**
     * Liste tous les objectifs
     */
    public function index()
    {
        $objectifs = $this->objectifModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $objectifs
        ]);
    }

    /**
     * Afficher un objectif
     */
    public function show($id = null)
    {
        $objectif = $this->objectifModel->find($id);

        if (!$objectif) {

            return $this->failNotFound(
                'Objectif introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $objectif
        ]);
    }

    /**
     * Ajouter un objectif
     */
    public function create()
    {
        $data = [
            'nom' => trim(
                $this->request->getPost('nom')
            )
        ];

        /**
         * Validation simple
         */
        if (empty($data['nom'])) {

            return $this->failValidationErrors(
                'Le nom est obligatoire'
            );
        }

        if (!$this->objectifModel->insert($data)) {

            return $this->failValidationErrors(
                $this->objectifModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Objectif créé'
        ]);
    }

    /**
     * Modifier un objectif
     */
    public function update($id = null)
    {
        $objectif = $this->objectifModel->find($id);

        if (!$objectif) {

            return $this->failNotFound(
                'Objectif inexistant'
            );
        }

        $data = [
            'nom' => trim(
                $this->request->getVar('nom')
            )
        ];

        /**
         * Validation
         */
        if (empty($data['nom'])) {

            return $this->failValidationErrors(
                'Le nom est obligatoire'
            );
        }

        $this->objectifModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Objectif modifié'
        ]);
    }

    /**
     * Supprimer un objectif
     */
    public function delete($id = null)
    {
        $objectif = $this->objectifModel->find($id);

        if (!$objectif) {

            return $this->failNotFound(
                'Objectif inexistant'
            );
        }

        $this->objectifModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Objectif supprimé'
        ]);
    }
}