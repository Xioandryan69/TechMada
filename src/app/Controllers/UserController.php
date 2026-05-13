<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Liste utilisateurs
     */
    public function index()
    {
        $users = $this->userModel->findAll();

        return $this->respond([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Détail utilisateur simple
     */
    public function show($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound("Utilisateur introuvable");
        }

        return $this->respond([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Profil complet utilisateur (JOIN)
     */
    public function profile($id)
    {
        $profile = $this->userModel->getProfileSummary($id);

        if (!$profile) {
            return $this->failNotFound("Profil introuvable");
        }

        return $this->respond([
            'success' => true,
            'data' => $profile
        ]);
    }

    /**
     * Créer utilisateur (inscription)
     */
    public function create()
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_BCRYPT
            ),
            'genre' => $this->request->getPost('genre'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'role' => 'user'
        ];

        if (empty($data['email']) || empty($data['password'])) {
            return $this->failValidationErrors("email et password obligatoires");
        }

        $this->userModel->insert($data);

        return $this->respondCreated([
            'success' => true,
            'message' => 'Utilisateur créé'
        ]);
    }

    /**
     * Modifier utilisateur
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound("Utilisateur introuvable");
        }

        $data = [
            'nom' => $this->request->getVar('nom'),
            'prenom' => $this->request->getVar('prenom'),
            'email' => $this->request->getVar('email'),
            'genre' => $this->request->getVar('genre'),
            'date_naissance' => $this->request->getVar('date_naissance')
        ];

        $this->userModel->update($id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Utilisateur modifié'
        ]);
    }

    /**
     * Supprimer utilisateur
     */
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound("Utilisateur introuvable");
        }

        $this->userModel->delete($id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Utilisateur supprimé'
        ]);
    }
}