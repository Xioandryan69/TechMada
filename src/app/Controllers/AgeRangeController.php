<?php

namespace App\Controllers;

use App\Models\AgeRangeModel;
use CodeIgniter\RESTful\ResourceController;

class AgeRangeController extends ResourceController
{
    protected $ageRangeModel;

    public function __construct()
    {
        $this->ageRangeModel = new AgeRangeModel();
    }

    /**
     * Liste toutes les tranches d'âge
     */
    public function index()
    {
        $ageRanges = $this->ageRangeModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $ageRanges
        ]);
    }

    /**
     * Afficher une tranche d'âge
     */
    public function show($id = null)
    {
        $ageRange = $this->ageRangeModel->find($id);

        if (!$ageRange) {

            return $this->failNotFound(
                'Tranche d\'âge introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $ageRange
        ]);
    }

    /**
     * Ajouter une tranche d'âge
     */
    public function create()
    {
        $data = [
            'age_min' => $this->request->getPost('age_min'),
            'age_max' => $this->request->getPost('age_max'),
        ];

        if (!$this->ageRangeModel->insert($data)) {

            return $this->failValidationErrors(
                $this->ageRangeModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Tranche d\'âge créée'
        ]);
    }

    /**
     * Modifier une tranche d'âge
     */
    public function update($id = null)
    {
        $ageRange = $this->ageRangeModel->find($id);

        if (!$ageRange) {

            return $this->failNotFound(
                'Tranche d\'âge inexistante'
            );
        }

        $data = [
            'age_min' => $this->request->getVar('age_min'),
            'age_max' => $this->request->getVar('age_max'),
        ];

        $this->ageRangeModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Tranche d\'âge modifiée'
        ]);
    }

    /**
     * Supprimer une tranche d'âge
     */
    public function delete($id = null)
    {
        $ageRange = $this->ageRangeModel->find($id);

        if (!$ageRange) {

            return $this->failNotFound(
                'Tranche d\'âge inexistante'
            );
        }

        $this->ageRangeModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Tranche d\'âge supprimée'
        ]);
    }
}