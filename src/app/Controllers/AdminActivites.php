<?php

namespace App\Controllers;

use App\Models\ActiviteModel;

class AdminActivites extends BaseController
{
    protected $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteModel();
    }

    // 1. Liste des Activités
    public function index()
    {
        $data['activites'] = $this->activiteModel->findAll();
        return view('admin/activites/index', $data);
    }

    // 2. Afficher le formulaire de création
    public function create()
    {
        return view('admin/activites/create');
    }

    // 3. Sauvegarder  nouveau Activité
    public function store()
    {
        $this->activiteModel->save([
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'calories_brulees'       => $this->request->getPost('calories_brulees'),
            'duree_minutes'           => $this->request->getPost('duree_minutes')
            ]);

        session()->setFlashdata('success', 'Activité créé avec succès.');
        return redirect()->to('admin/activites');
    }

    // 4. Afficher le formulaire de modification
    public function edit($id)
    {
        $data['activite'] = $this->activiteModel->find($id);
        
        if (empty($data['activite'])) {
            return redirect()->to('admin/activites')->with('error', 'Activité introuvable.');
        }

        return view('admin/activites/edit', $data);
    }

    // 5. Mettre à jour le Activité
    public function update($id)
    {
        $this->activiteModel->update($id, [
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'calories_brulees'       => $this->request->getPost('calories_brulees'),
            'duree_minutes'           => $this->request->getPost('duree_minutes')
        ]);

        session()->setFlashdata('success', 'Activité mis à jour.');
        return redirect()->to('admin/activites');
    }

    // 6. Supprimer Activité
    public function delete($id)
    {
        $this->activiteModel->delete($id);
        session()->setFlashdata('success', 'Activité supprimé.');
        return redirect()->to('admin/activites');
    }
}