<?php

namespace App\Controllers;

use App\Models\HealthRuleModel;
use CodeIgniter\RESTful\ResourceController;

class HealthRuleController extends ResourceController
{
    protected $healthRuleModel;

    public function __construct()
    {
        $this->healthRuleModel = new HealthRuleModel();
    }

    /**
     * Liste toutes les règles santé
     */
    public function index()
    {
        $rules = $this->healthRuleModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Afficher une règle santé
     */
    public function show($id = null)
    {
        $rule = $this->healthRuleModel->find($id);

        if (!$rule) {

            return $this->failNotFound(
                'Règle santé introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $rule
        ]);
    }

    /**
     * Ajouter une règle santé
     */
    public function create()
    {
        $data = [
            'type' => $this->request->getPost('type'),
            'age_min' => $this->request->getPost('age_min'),
            'age_max' => $this->request->getPost('age_max'),
            'genre' => $this->request->getPost('genre'),
            'valeur_min' => $this->request->getPost('valeur_min'),
            'valeur_max' => $this->request->getPost('valeur_max'),
        ];

        /**
         * Validation logique
         */
        if ($data['age_min'] > $data['age_max']) {

            return $this->failValidationErrors(
                'age_min doit être inférieur à age_max'
            );
        }

        if ($data['valeur_min'] > $data['valeur_max']) {

            return $this->failValidationErrors(
                'valeur_min doit être inférieure à valeur_max'
            );
        }

        if (!$this->healthRuleModel->insert($data)) {

            return $this->failValidationErrors(
                $this->healthRuleModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Règle santé créée'
        ]);
    }

    /**
     * Modifier une règle santé
     */
    public function update($id = null)
    {
        $rule = $this->healthRuleModel->find($id);

        if (!$rule) {

            return $this->failNotFound(
                'Règle inexistante'
            );
        }

        $data = [
            'type' => $this->request->getVar('type'),
            'age_min' => $this->request->getVar('age_min'),
            'age_max' => $this->request->getVar('age_max'),
            'genre' => $this->request->getVar('genre'),
            'valeur_min' => $this->request->getVar('valeur_min'),
            'valeur_max' => $this->request->getVar('valeur_max'),
        ];

        /**
         * Validation logique
         */
        if ($data['age_min'] > $data['age_max']) {

            return $this->failValidationErrors(
                'age_min doit être inférieur à age_max'
            );
        }

        if ($data['valeur_min'] > $data['valeur_max']) {

            return $this->failValidationErrors(
                'valeur_min doit être inférieure à valeur_max'
            );
        }

        $this->healthRuleModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Règle santé modifiée'
        ]);
    }

    /**
     * Supprimer une règle santé
     */
    public function delete($id = null)
    {
        $rule = $this->healthRuleModel->find($id);

        if (!$rule) {

            return $this->failNotFound(
                'Règle inexistante'
            );
        }

        $this->healthRuleModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Règle santé supprimée'
        ]);
    }
}