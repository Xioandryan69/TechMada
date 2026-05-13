<?php

namespace App\Controllers;

use App\Models\CodeModel;
use CodeIgniter\RESTful\ResourceController;

class CodeController extends ResourceController
{
    protected $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodeModel();
    }

    /**
     * Liste tous les codes
     */
    public function index()
    {
        $codes = $this->codeModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $codes
        ]);
    }

    /**
     * Afficher un code
     */
    public function show($id = null)
    {
        $code = $this->codeModel->find($id);

        if (!$code) {

            return $this->failNotFound(
                'Code introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $code
        ]);
    }

    /**
     * Créer un code
     */
    public function create()
    {
        $data = [
            'code' => trim(
                $this->request->getPost('code')
            ),

            'valeur' => $this->request
                ->getPost('valeur'),

            'utilise' => 0
        ];

        if (!$this->codeModel->insert($data)) {

            return $this->failValidationErrors(
                $this->codeModel->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' => 'Code créé'
        ]);
    }

    /**
     * Vérifier validité code
     */
    public function verifier()
    {
        $codeString = trim(
            $this->request->getPost('code')
        );

        if (empty($codeString)) {

            return $this->failValidationErrors(
                'Code obligatoire'
            );
        }

        $code = $this->codeModel
            ->trouverValide($codeString);

        if (!$code) {

            return $this->failNotFound(
                'Code invalide ou déjà utilisé'
            );
        }

        return $this->respond([
            'success' => true,
            'message' => 'Code valide',
            'data' => $code
        ]);
    }

    /**
     * Marquer code utilisé
     */
    public function utiliser($id = null)
    {
        $code = $this->codeModel->find($id);

        if (!$code) {

            return $this->failNotFound(
                'Code inexistant'
            );
        }

        if ($code['utilise']) {

            return $this->fail(
                'Code déjà utilisé',
                400
            );
        }

        $result = $this->codeModel
            ->marquerUtilise($id);

        if (!$result) {

            return $this->failServerError(
                'Erreur mise à jour'
            );
        }

        return $this->respond([
            'success' => true,
            'message' => 'Code marqué utilisé'
        ]);
    }

    /**
     * Supprimer code
     */
    public function delete($id = null)
    {
        $code = $this->codeModel->find($id);

        if (!$code) {

            return $this->failNotFound(
                'Code inexistant'
            );
        }

        $this->codeModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Code supprimé'
        ]);
    }
}