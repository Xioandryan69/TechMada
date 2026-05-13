<?php

namespace App\Controllers;

use App\Models\UserHealthModel;

class UsersAuth extends BaseController
{
    // ─────────────────────────────────────────
    //  CONNEXION
    // ─────────────────────────────────────────

    public function login()
    {
        if (session()->get('isUsersLoggedIn')) {
            return redirect()->to('/users/homepage');
        }

        return view('users/login');
    }

    public function loginCheck()
    {
        $session  = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $username)
                          ->where('role', 'user')
                          ->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id'              => $user['id'],
                'username'        => $user['nom'] . ' ' . $user['prenom'],
                'email'           => $user['email'],
                'isUsersLoggedIn' => true,
            ]);
            return redirect()->to('/users/homepage');
        }

        $session->setFlashdata('error', 'Email ou mot de passe incorrect, ou accès non autorisé.');
        return redirect()->to('/users/login');
    }

    // ─────────────────────────────────────────
    //  INSCRIPTION — ÉTAPE 1 : infos personnelles
    // ─────────────────────────────────────────

    public function register()
    {
        if (session()->get('isUsersLoggedIn')) {
            return redirect()->to('/users/homepage');
        }

        return view('users/register_step1');
    }

    public function registerStep1()
    {
        $rules = [
            'nom'              => 'required|min_length[2]|max_length[100]',
            'prenom'           => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'genre'            => 'required|in_list[H,F]',
            'date_naissance'   => 'required|valid_date[Y-m-d]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'nom'              => [
                'required'   => 'Le nom est obligatoire.',
                'min_length' => 'Le nom doit comporter au moins 2 caractères.',
            ],
            'prenom'           => [
                'required'   => 'Le prénom est obligatoire.',
                'min_length' => 'Le prénom doit comporter au moins 2 caractères.',
            ],
            'email'            => [
                'required'    => "L'adresse e-mail est obligatoire.",
                'valid_email' => 'Veuillez saisir une adresse e-mail valide.',
                'is_unique'   => 'Cette adresse e-mail est déjà utilisée.',
            ],
            'genre'            => [
                'required' => 'Le genre est obligatoire.',
                'in_list'  => 'Veuillez choisir un genre valide.',
            ],
            'date_naissance'   => [
                'required'   => 'La date de naissance est obligatoire.',
                'valid_date' => "La date de naissance n'est pas valide.",
            ],
            'password'         => [
                'required'   => 'Le mot de passe est obligatoire.',
                'min_length' => 'Le mot de passe doit comporter au moins 6 caractères.',
            ],
            'password_confirm' => [
                'required' => 'Veuillez confirmer votre mot de passe.',
                'matches'  => 'Les mots de passe ne correspondent pas.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return view('users/register_step1', [
                'validation' => $this->validator,
            ]);
        }

        // Stocker les données en session (mot de passe haché immédiatement)
        session()->set('register_step1', [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'genre'          => $this->request->getPost('genre'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'password_hash'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/users/register/step2');
    }

    // ─────────────────────────────────────────
    //  INSCRIPTION — ÉTAPE 2 : infos de santé
    // ─────────────────────────────────────────

    public function registerStep2()
    {
        if (session()->get('isUsersLoggedIn')) {
            return redirect()->to('/users/homepage');
        }

        // Impossible d'accéder à l'étape 2 sans avoir validé l'étape 1
        if (! session()->get('register_step1')) {
            session()->setFlashdata('error', "Veuillez d'abord remplir vos informations personnelles.");
            return redirect()->to('/users/register');
        }

        return view('users/register_step2');
    }

    public function registerStep2Check()
    {
        $step1 = session()->get('register_step1');

        if (! $step1) {
            session()->setFlashdata('error', 'Session expirée. Veuillez recommencer votre inscription.');
            return redirect()->to('/users/register');
        }

        $rules = [
            'taille' => 'required|numeric|greater_than[99]|less_than[251]',
            'poids'  => 'required|numeric|greater_than[19]|less_than[301]',
        ];

        $messages = [
            'taille' => [
                'required'     => 'La taille est obligatoire.',
                'numeric'      => 'La taille doit être un nombre.',
                'greater_than' => 'La taille doit être supérieure à 100 cm.',
                'less_than'    => 'La taille doit être inférieure à 250 cm.',
            ],
            'poids' => [
                'required'     => 'Le poids est obligatoire.',
                'numeric'      => 'Le poids doit être un nombre.',
                'greater_than' => 'Le poids doit être supérieur à 20 kg.',
                'less_than'    => 'Le poids doit être inférieur à 300 kg.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return view('users/register_step2', [
                'validation' => $this->validator,
            ]);
        }

        $taille = (float) $this->request->getPost('taille');
        $poids  = (float) $this->request->getPost('poids');
        $tailleM = $taille / 100;

        // Création de l'utilisateur
        $userModel = new \App\Models\UserModel();
        $userId = $userModel->insert([
            'nom'            => $step1['nom'],
            'prenom'         => $step1['prenom'],
            'email'          => $step1['email'],
            'password'       => $step1['password_hash'],
            'genre'          => $step1['genre'],
            'date_naissance' => $step1['date_naissance'],
            'role'           => 'user',
        ]);

        // Enregistrement de la mesure de santé initiale via le modèle (calcul IMC centralisé)
        $userHealthModel = new UserHealthModel();
        $userHealthModel->sauvegarder((int) $userId, [
            'taille' => $tailleM,
            'poids' => $poids,
            'date_mesure' => date('Y-m-d H:i:s'),
        ]);

        // Nettoyage de la session d'inscription
        session()->remove('register_step1');

        session()->setFlashdata('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
        return redirect()->to('/users/login');
    }

    // ─────────────────────────────────────────
    //  DÉCONNEXION
    // ─────────────────────────────────────────

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/users/login');
    }
}