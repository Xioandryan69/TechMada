<?php

namespace App\Controllers;

use App\Models\RecommendationModel;
use CodeIgniter\RESTful\ResourceController;

class RecommendationController
    extends ResourceController
{
    protected $recommendationModel;

    public function __construct()
    {
        $this->recommendationModel =
            new RecommendationModel();
    }

    /**
     * Liste recommandations
     */
    public function index()
    {
        $data =
            $this->recommendationModel
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Détail recommandation
     */
    public function show($id = null)
    {
        $recommendation =
            $this->recommendationModel
                ->find($id);

        if (!$recommendation) {

            return $this->failNotFound(
                'Recommandation introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $recommendation
        ]);
    }

    /**
     * Créer recommandation simple
     */
    public function create()
    {
        $data = [

            'user_id' =>
                $this->request
                    ->getPost('user_id'),

            'regime_id' =>
                $this->request
                    ->getPost('regime_id'),

            'activite_id' =>
                $this->request
                    ->getPost('activite_id'),

            'date_debut' =>
                $this->request
                    ->getPost('date_debut'),

            'date_fin' =>
                $this->request
                    ->getPost('date_fin')
        ];

        if (!$this->recommendationModel
                ->insert($data)) {

            return $this
                ->failValidationErrors(

                    $this->recommendationModel
                        ->errors()
                );
        }

        return $this->respondCreated([
            'success' => true,
            'message' =>
                'Recommandation créée'
        ]);
    }

    /**
     * Modifier recommandation
     */
    public function update($id = null)
    {
        $recommendation =
            $this->recommendationModel
                ->find($id);

        if (!$recommendation) {

            return $this->failNotFound(
                'Recommandation inexistante'
            );
        }

        $data = [

            'regime_id' =>
                $this->request
                    ->getVar('regime_id'),

            'activite_id' =>
                $this->request
                    ->getVar('activite_id'),

            'date_debut' =>
                $this->request
                    ->getVar('date_debut'),

            'date_fin' =>
                $this->request
                    ->getVar('date_fin')
        ];

        $this->recommendationModel
            ->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' =>
                'Recommandation modifiée'
        ]);
    }

    /**
     * Supprimer recommandation
     */
    public function delete($id = null)
    {
        $recommendation =
            $this->recommendationModel
                ->find($id);

        if (!$recommendation) {

            return $this->failNotFound(
                'Recommandation inexistante'
            );
        }

        $this->recommendationModel
            ->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' =>
                'Recommandation supprimée'
        ]);
    }

    /**
     * Générer recommandations utilisateur
     */
    public function enregistrer()
    {
        $user_id =
            (int) $this->request
                ->getPost('user_id');

        $regimes =
            $this->request
                ->getPost('regimes');

        $activites =
            $this->request
                ->getPost('activites') ?? [];

        if (empty($user_id)) {

            return $this->failValidationErrors(
                'user_id obligatoire'
            );
        }

        if (empty($regimes)) {

            return $this->failValidationErrors(
                'regimes obligatoire'
            );
        }

        $this->recommendationModel
            ->enregistrer(
                $user_id,
                $regimes,
                $activites
            );

        return $this->respond([
            'success' => true,
            'message' =>
                'Recommandations enregistrées'
        ]);
    }

    /**
     * Recommandations détaillées
     */
    public function utilisateur($user_id)
    {
        $data =
            $this->recommendationModel
                ->getAvecDetails($user_id);

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Recommandations actives
     */
    public function actives($user_id)
    {
        $data =
            $this->recommendationModel
                ->getActives($user_id);

        return $this->respond([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Supprimer recommandations utilisateur
     */
    public function supprimerUser($user_id)
    {
        $this->recommendationModel
            ->supprimerParUser($user_id);

        return $this->respond([
            'success' => true,
            'message' =>
                'Recommandations utilisateur supprimées'
        ]);
    }
}