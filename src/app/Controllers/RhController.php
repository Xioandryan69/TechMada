<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;

class RhController extends BaseController
{
    public function demandes()
    {
        $model = new CongeModel();

        return view('rh/demandes', [
            'conges' => $model->getPendingLeaves()
        ]);
    }

    public function valider($id)
    {
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($id);

        if (!$conge) {
            return redirect()->back();
        }

        // 1. update congé
        $congeModel->update($id, [
            'statut' => 'valide',
            'traite_par' => session()->get('user_id')
        ]);

        // 2. update solde
        $annee = date('Y');

        $soldeModel->updateJoursPris(
            $conge['employe_id'],
            $conge['type_conge_id'],
            $annee,
            $conge['nb_jours']
        );

        return redirect()->back();
    }

    public function refuser($id)
    {
        $model = new CongeModel();

        $model->update($id, [
            'statut' => 'refuse',
            'traite_par' => session()->get('user_id')
        ]);

        return redirect()->back();
    }
}