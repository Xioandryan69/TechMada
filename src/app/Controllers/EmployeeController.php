<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\EmployeeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeeController extends BaseController
{
    private function roleIdFromName(string $roleName): int
    {
        $row = db_connect()->table('roles')->where('nom', $roleName)->get()->getRowArray();

        return (int) ($row['id'] ?? 0);
    }

    public function index()
    {
        return $this->gestion();
    }

    public function dashboard()
    {
        $congeModel = new CongeModel();
        $employeeId = (int) session()->get('user_id');

        return view('dashboardEmployee', [
            'conges' => $employeeId > 0 ? $congeModel->getLeavesByEmployee($employeeId) : [],
            'soldes' => $employeeId > 0 ? (new SoldeModel())->getByEmployeeAndYear($employeeId, (int) date('Y')) : []
        ]);
    }

    public function demandes()
    {
        $congeModel = new CongeModel();
        $employeeId = (int) session()->get('user_id');

        return view('demandeEmployee', [
            'conges' => $employeeId > 0 ? $congeModel->getLeavesByEmployee($employeeId) : []
        ]);
    }

    public function formulaireDemande()
    {
        return view('formulaireDemande', [
            'typesConge' => (new TypeCongeModel())->findAll(),
            'soldes' => ((int) session()->get('user_id') > 0)
                ? (new SoldeModel())->getByEmployeeAndYear((int) session()->get('user_id'), (int) date('Y'))
                : []
        ]);
    }

    public function storeDemande()
    {
        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
            'motif' => 'required|min_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez corriger les champs du formulaire.');
        }

        $dateDebut = new \DateTime($this->request->getPost('date_debut'));
        $dateFin = new \DateTime($this->request->getPost('date_fin'));

        if ($dateFin < $dateDebut) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit être postérieure à la date de début.');
        }

        $congeModel = new CongeModel();
        $congeModel->insert([
            'employe_id' => (int) session()->get('user_id'),
            'type_conge_id' => (int) $this->request->getPost('type_conge_id'),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
            'nb_jours' => $dateDebut->diff($dateFin)->days + 1,
            'motif' => trim((string) $this->request->getPost('motif')),
            'statut' => 'en_attente',
        ]);

        return redirect()->to('/employee/conges')->with('success', 'Votre demande de congé a bien été soumise.');
    }

    public function annulerDemande($id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find((int) $id);

        if (! $conge || (int) $conge['employe_id'] !== (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if (($conge['statut'] ?? '') !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        $congeModel->update((int) $id, [
            'statut' => 'refuse',
            'commentaire_rh' => 'Annulé par l\'employé'
        ]);

        return redirect()->back()->with('success', 'Demande annulée.');
    }

    public function gestion()
    {
        $model = new EmployeeModel();

        return view('gestionEmployee', [
            'employees' => $model->getWithDepartment(),
            'departements' => (new DepartementModel())->findAll()
        ]);
    }

    public function store()
    {
        $employeeModel = new EmployeeModel();

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id' => $this->roleIdFromName((string) $this->request->getPost('role')),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'actif' => 1,
        ];

        if (! $employeeModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Impossible de créer l’employé.');
        }

        return redirect()->to('/admin/employes')->with('success', 'Employé créé avec succès.');
    }

    public function update($id)
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find((int) $id);

        if (! $employee) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $data = array_filter([
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => trim((string) $this->request->getPost('email')),
            'role_id' => $this->request->getPost('role') ? $this->roleIdFromName((string) $this->request->getPost('role')) : null,
            'departement_id' => $this->request->getPost('departement_id'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'actif' => $this->request->getPost('actif'),
            'password' => $this->request->getPost('password') ? password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT) : null,
        ], static fn ($value) => $value !== null && $value !== '');

        $employeeModel->update((int) $id, $data);

        return redirect()->to('/admin/employes')->with('success', 'Employé mis à jour.');
    }

    public function delete($id)
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find((int) $id);

        if (! $employee) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $employeeModel->update((int) $id, ['actif' => 0]);

        return redirect()->to('/admin/employes')->with('success', 'Employé désactivé.');
    }

    public function reactiver($id)
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find((int) $id);

        if (! $employee) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $employeeModel->update((int) $id, ['actif' => 1]);

        return redirect()->to('/admin/employes')->with('success', 'Employé réactivé.');
    }

    public function profil()
    {
        $employeeId = (int) session()->get('user_id');
        $model = new EmployeeModel();
        $congeModel = new CongeModel();

        return view('profilEmployee', [
            'conges' => $employeeId > 0 ? $congeModel->getLeavesByEmployee($employeeId) : [],
            'soldes' => $employeeId > 0 ? (new SoldeModel())->getByEmployeeAndYear($employeeId, (int) date('Y')) : [],
            'employee' => $model->find($employeeId),
            'departements' => (new DepartementModel())->findAll()
        ]);
    }
}