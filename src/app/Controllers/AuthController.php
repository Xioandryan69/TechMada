<?php

namespace App\Controllers;

use Config\Database;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('login', [
            'defaultEmail' => '',
            'defaultPassword' => '',
        ]);
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('erreur', 'Veuillez remplir tous les champs.');
        }

        $db = Database::connect();
        $admin = $db->table('admin')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (!$admin || !password_verify($password, $admin['password'])) {
            return redirect()->back()->with('erreur', 'Email ou mot de passe incorrect.');
        }

        session()->set('user', [
            'id' => $admin['id'],
            'email' => $admin['email'],
        ]);

        return redirect()->to('/students');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
