<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use CodeIgniter\RESTful\ResourceController;

class RegimeController extends ResourceController
{
    protected $regimeModel;

    public function __construct()
    {
        $this->regimeModel =
            new RegimeModel();
    }

    /**
     * Liste tous les régimes
     */
    public function index()
    {
        $regimes =
            $this->regimeModel
                ->findAll();

        return $this->respond([
            'success' => true,
            'data' => $regimes
        ]);
    }

    /**
     * Afficher un régime
     */
    public function show($id = null)
    {
        $regime =
            $this->regimeModel
                ->find($id);

        if (!$regime) {

            return $this->failNotFound(
                'Régime introuvable'
            );
        }

        return $this->respond([
            'success' => true,
            'data' => $regime
        ]);
    }

    /**
     * Ajouter un régime
     */
    public function create()
    {
        $data = [

            'nom' => trim(
                $this->request
                    ->getPost('nom')
            ),

            'description' =>
                $this->request
                    ->getPost('description'),

            'regime_user_id' =>
                $this->request
                    ->getPost('regime_user_id'),

            'calories' =>
                $this->request
                    ->getPost('calories'),

            'prix' =>
                $this->request
                    ->getPost('prix'),

            'duree_jours' =>
                $this->request
                    ->getPost('duree_jours'),

            'pourcentage_viande' =>
                $this->request
                    ->getPost(
                        'pourcentage_viande'
                    ),

            'pourcentage_poisson' =>
                $this->request
                    ->getPost(
                        'pourcentage_poisson'
                    ),

            'pourcentage_volaille' =>
                $this->request
                    ->getPost(
                        'pourcentage_volaille'
                    )
        ];

        /**
         * Validation logique
         */
        $somme =
            $data['pourcentage_viande']
            + $data['pourcentage_poisson']
            + $data['pourcentage_volaille'];

        if ($somme > 100) {

            return $this->failValidationErrors(
                'La somme des pourcentages dépasse 100%'
            );
        }

        if (!$this->regimeModel
                ->insert($data)) {

            return $this->failValidationErrors(

                $this->regimeModel
                    ->errors()
            );
        }

        return $this->respondCreated([
            'success' => true,
            'message' =>
                'Régime créé'
        ]);
    }

    /**
     * Modifier régime
     */
    public function update($id = null)
    {
        $regime =
            $this->regimeModel
                ->find($id);

        if (!$regime) {

            return $this->failNotFound(
                'Régime inexistant'
            );
        }

        $data = [

            'nom' => trim(
                $this->request
                    ->getVar('nom')
            ),

            'description' =>
                $this->request
                    ->getVar('description'),

            'regime_user_id' =>
                $this->request
                    ->getVar('regime_user_id'),

            'calories' =>
                $this->request
                    ->getVar('calories'),

            'prix' =>
                $this->request
                    ->getVar('prix'),

            'duree_jours' =>
                $this->request
                    ->getVar('duree_jours'),

            'pourcentage_viande' =>
                $this->request
                    ->getVar(
                        'pourcentage_viande'
                    ),

            'pourcentage_poisson' =>
                $this->request
                    ->getVar(
                        'pourcentage_poisson'
                    ),

            'pourcentage_volaille' =>
                $this->request
                    ->getVar(
                        'pourcentage_volaille'
                    )
        ];

        /**
         * Validation logique
         */
        $somme =
            $data['pourcentage_viande']
            + $data['pourcentage_poisson']
            + $data['pourcentage_volaille'];

        if ($somme > 100) {

            return $this->failValidationErrors(
                'La somme des pourcentages dépasse 100%'
            );
        }

        $this->regimeModel
            ->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' =>
                'Régime modifié'
        ]);
    }

    /**
     * Supprimer régime
     */
    public function delete($id = null)
    {
        $regime =
            $this->regimeModel
                ->find($id);

        if (!$regime) {

            return $this->failNotFound(
                'Régime inexistant'
            );
        }

        $this->regimeModel
            ->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' =>
                'Régime supprimé'
        ]);
    }
}