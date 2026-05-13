<?php

namespace App\Controllers;

use App\Models\ImcCategoryModel;
use CodeIgniter\RESTful\ResourceController;

class ImcCategoryController extends ResourceController
{
    protected $imcCategoryModel;

    public function __construct()
    {
        $this->imcCategoryModel = new ImcCategoryModel();
    }

    /**
     * Liste toutes les catégories IMC
     */
    public function index()
    {
        $categories = $this->imcCategoryModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Afficher une catégorie IMC
     */
    public function show($id = null)
    {
        $category = $this->imcCategoryModel->find($id);

        if (!$category) {

            return $this->failNotFound(
                'Catégorie IMC introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Ajouter catégorie IMC
     */
    public function create()
    {
        $data = [
            'min_value' => $this->request
                ->getPost('min_value'),

            'max_value' => $this->request
                ->getPost('max_value'),

            'label' => trim(
                $this->request->getPost('label')
            ),
        ];

        /**
         * Validation logique
         */
        if ($data['min_value'] >= $data['max_value']) {

            return $this->failValidationErrors(
                'min_value doit être inférieur à max_value'
            );
        }

        if (!$this->imcCategoryModel->insert($data)) {

            return $this->failValidationErrors(
                $this->imcCategoryModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Catégorie IMC créée'
        ]);
    }

    /**
     * Modifier catégorie IMC
     */
    public function update($id = null)
    {
        $category = $this->imcCategoryModel->find($id);

        if (!$category) {

            return $this->failNotFound(
                'Catégorie IMC inexistante'
            );
        }

        $data = [
            'min_value' => $this->request
                ->getVar('min_value'),

            'max_value' => $this->request
                ->getVar('max_value'),

            'label' => trim(
                $this->request->getVar('label')
            ),
        ];

        /**
         * Validation logique
         */
        if ($data['min_value'] >= $data['max_value']) {

            return $this->failValidationErrors(
                'min_value doit être inférieur à max_value'
            );
        }

        $this->imcCategoryModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Catégorie IMC modifiée'
        ]);
    }

    /**
     * Supprimer catégorie IMC
     */
    public function delete($id = null)
    {
        $category = $this->imcCategoryModel->find($id);

        if (!$category) {

            return $this->failNotFound(
                'Catégorie IMC inexistante'
            );
        }

        $this->imcCategoryModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Catégorie IMC supprimée'
        ]);
    }
}