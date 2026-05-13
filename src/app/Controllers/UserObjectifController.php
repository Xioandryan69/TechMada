<?php

namespace App\Controllers;

use App\Models\UserObjectifModel;
use CodeIgniter\RESTful\ResourceController;

class UserObjectifController extends ResourceController
{
    protected $userObjectifModel;

    public function __construct()
    {
        $this->userObjectifModel = new UserObjectifModel();
    }

    /**
     * Liste toutes les relations user-objectifs
     */
    public function index()
    {
        $data = $this->userObjectifModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Voir objectif d’un utilisateur
     */
    public function show($user_id = null)
    {
        $data = $this->userObjectifModel
            ->where('user_id', $user_id)
            ->first();

        if (!$data) {
            return $this->failNotFound("Objectif utilisateur introuvable");
        }

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Créer ou mettre à jour objectif utilisateur
     */
    public function save()
    {
        $user_id = $this->request->getPost('user_id');
        $objectif_id = $this->request->getPost('objectif_id');

        if (!$user_id || !$objectif_id) {
            return $this->failValidationErrors(
                "user_id et objectif_id obligatoires"
            );
        }

        $result = $this->userObjectifModel->sauvegarder(
            (int)$user_id,
            (int)$objectif_id
        );

        if (!$result) {
            return $this->failServerError(
                "Erreur lors de l'enregistrement"
            );
        }

        return $this->respond([
            'success' => true,
            'message' => 'Objectif utilisateur enregistré'
        ]);
    }

    /**
     * Supprimer objectif d’un utilisateur
     */
    public function delete($user_id = null)
    {
        $exists = $this->userObjectifModel
            ->where('user_id', $user_id)
            ->first();

        if (!$exists) {
            return $this->failNotFound("Aucune donnée trouvée");
        }

        $this->userObjectifModel
            ->where('user_id', $user_id)
            ->delete();

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Objectif supprimé'
        ]);
    }
}