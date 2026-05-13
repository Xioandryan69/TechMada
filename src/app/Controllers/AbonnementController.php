<?php

namespace App\Controllers;

use App\Models\AbonnementModel;
use CodeIgniter\Controller;

class AbonnementController extends Controller
{
    protected $abonnementModel;

    public function __construct()
    {
        $this->abonnementModel = new AbonnementModel();
    }

    /**
     * Affiche abonnement actif utilisateur
     */
    public function index($userId)
    {
        $abonnement = $this->abonnementModel
            ->getActifParUser($userId);

        return $this->response->setJSON([
            'success' => true,
            'data' => $abonnement
        ]);
    }

    /**
     * Active abonnement GOLD
     */
    public function activerGold($userId)
    {
        $result = $this->abonnementModel
            ->activerGold($userId);

        if (!$result) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Erreur activation abonnement'
                ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Abonnement GOLD activé'
        ]);
    }
}