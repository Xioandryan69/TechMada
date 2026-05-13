<?php

namespace App\Controllers;

use App\Models\UserHealthModel;
use CodeIgniter\RESTful\ResourceController;

class UserHealthController extends ResourceController
{
    protected $userHealthModel;

    public function __construct()
    {
        $this->userHealthModel = new UserHealthModel();
    }

    /**
     * Liste toutes les données santé
     */
    public function index()
    {
        $data = $this->userHealthModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Voir une donnée santé
     */
    public function show($id = null)
    {
        $data = $this->userHealthModel->find($id);

        if (!$data) {
            return $this->failNotFound("Donnée santé introuvable");
        }

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Récupérer santé par utilisateur
     */
    public function parUser($user_id)
    {
        $data = $this->userHealthModel->getParUser($user_id);

        if (!$data) {
            return $this->failNotFound("Aucune donnée santé pour cet utilisateur");
        }

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Créer ou mettre à jour données santé
     */
    public function save()
    {
        $user_id = $this->request->getPost('user_id') ?? session()->get('id');

        $data = [
            'taille' => $this->request->getPost('taille'),
            'poids'  => $this->request->getPost('poids'),
            'date_mesure' => date('Y-m-d H:i:s')
        ];

        if (!$user_id) {
            return $this->failValidationErrors("user_id obligatoire");
        }

        $result = $this->userHealthModel->sauvegarder($user_id, $data);

        if (!$result) {
            return $this->failServerError("Erreur lors de la sauvegarde");
        }

        return $this->respond([
            'success' => true,
            'message' => 'Données santé enregistrées'
        ]);
    }

    /**
     * Front office: données santé de l'utilisateur connecté
     */
    public function me()
    {
        $userId = (int) session()->get('id');

        if ($userId <= 0) {
            return $this->failUnauthorized('Utilisateur non connecté');
        }

        $data = $this->userHealthModel->getParUser($userId);

        if (!$data) {
            return $this->failNotFound('Aucune donnée santé pour cet utilisateur');
        }

        return $this->respond([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Front office: sauvegarde santé utilisateur connecté
     */
    public function saveMe()
    {
        $userId = (int) session()->get('id');

        if ($userId <= 0) {
            return $this->failUnauthorized('Utilisateur non connecté');
        }

        $data = [
            'taille' => $this->request->getPost('taille'),
            'poids'  => $this->request->getPost('poids'),
            'date_mesure' => date('Y-m-d H:i:s'),
        ];

        $result = $this->userHealthModel->sauvegarder($userId, $data);

        if (!$result) {
            return $this->failServerError('Erreur lors de la sauvegarde');
        }

        return $this->respond([
            'success' => true,
            'message' => 'Données santé enregistrées',
        ]);
    }

    /**
     * Calcul IMC sans sauvegarde
     */
    public function calculerIMC()
    {
        $poids = (float) $this->request->getPost('poids');
        $taille = (float) $this->request->getPost('taille');

        if ($poids <= 0 || $taille <= 0) {
            return $this->failValidationErrors("Données invalides");
        }

        $imc = $this->userHealthModel->calculerIMC($poids, $taille);

        $categorie = $this->userHealthModel->categorieIMC($imc);

        return $this->respond([
            'success' => true,
            'imc' => $imc,
            'categorie' => $categorie
        ]);
    }

    /**
     * Supprimer données santé utilisateur
     */
    public function delete($id = null)
    {
        $data = $this->userHealthModel->find($id);

        if (!$data) {
            return $this->failNotFound("Donnée inexistante");
        }

        $this->userHealthModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Donnée supprimée'
        ]);
    }
}