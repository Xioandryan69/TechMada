<?php

namespace App\Controllers;

use App\Models\ActiviteModel;
use CodeIgniter\RESTful\ResourceController;

class ActiviteController extends ResourceController
{
    protected $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteModel();
    }

    /**
     * Liste toutes les activités
     */
    public function index()
    {
        $activites = $this->activiteModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $activites
        ]);
    }

    /**
     * Affiche une activité par ID
     */
    public function show($id = null)
    {
        $activite = $this->activiteModel->find($id);

        if (!$activite) {

            return $this->failNotFound(
                'Activité introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $activite
        ]);
    }

    /**
     * Ajouter une activité
     */
    public function create()
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'calories_brulees' => $this->request->getPost('calories_brulees'),
            'duree_minutes' => $this->request->getPost('duree_minutes'),
        ];

        if (!$this->activiteModel->insert($data)) {

            return $this->failValidationErrors(
                $this->activiteModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Activité créée'
        ]);
    }

    /**
     * Modifier une activité
     */
    public function update($id = null)
    {
        $activite = $this->activiteModel->find($id);

        if (!$activite) {

            return $this->failNotFound(
                'Activité inexistante'
            );
        }

        $data = [
            'nom' => $this->request->getVar('nom'),
            'description' => $this->request->getVar('description'),
            'calories_brulees' => $this->request->getVar('calories_brulees'),
            'duree_minutes' => $this->request->getVar('duree_minutes'),
        ];

        $this->activiteModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Activité modifiée'
        ]);
    }

    /**
     * Supprimer activité
     */
    public function delete($id = null)
    {
        $activite = $this->activiteModel->find($id);

        if (!$activite) {

            return $this->failNotFound(
                'Activité inexistante'
            );
        }

        $this->activiteModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Activité supprimée'
        ]);
    }
}