<?php

namespace App\Controllers;

use App\Models\RegimeModel;

class AdminRegimes extends BaseController
{
    protected $regimeModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
    }

    // 1. Liste des régimes
    public function index()
    {
        $data['regimes'] = $this->regimeModel->findAll();
        return view('admin/regimes/index', $data);
    }

    // 2. Afficher le formulaire de création
    public function create()
    {
        return view('admin/regimes/create');
    }

    // 3. Sauvegarder le nouveau régime
    public function store()
    {
        $this->regimeModel->save([
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'calories'       => $this->request->getPost('calories'),
            'prix'           => $this->request->getPost('prix'),
            'duree_jours'    => $this->request->getPost('duree_jours'),
            'pourcentage_viande'   => $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson'  => $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            'regime_user_id' => 1 // Valeur par défaut pour l'exemple (lié à ton architecture de Base de données)
        ]);

        session()->setFlashdata('success', 'Régime créé avec succès.');
        return redirect()->to('admin/regimes');
    }

    // 4. Afficher le formulaire de modification
    public function edit($id)
    {
        $data['regime'] = $this->regimeModel->find($id);
        
        if (empty($data['regime'])) {
            return redirect()->to('admin/regimes')->with('error', 'Régime introuvable.');
        }

        return view('admin/regimes/edit', $data);
    }

    // 5. Mettre à jour le régime
    public function update($id)
    {
        $this->regimeModel->update($id, [
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'calories'       => $this->request->getPost('calories'),
            'prix'           => $this->request->getPost('prix'),
            'duree_jours'    => $this->request->getPost('duree_jours'),
            'pourcentage_viande'   => $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson'  => $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille')
        ]);

        session()->setFlashdata('success', 'Régime mis à jour.');
        return redirect()->to('admin/regimes');
    }

    // 6. Supprimer le régime
    public function delete($id)
    {
        $this->regimeModel->delete($id);
        session()->setFlashdata('success', 'Régime supprimé.');
        return redirect()->to('admin/regimes');
    }
}