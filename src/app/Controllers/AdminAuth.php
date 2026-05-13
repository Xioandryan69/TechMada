<?php

namespace App\Controllers;

class AdminAuth extends BaseController
{
    public function login()
    {
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/dashboard'); 
        }

        return view('admin/login');
    }

    public function loginCheck()
    {
        $session = session();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new \App\Models\UserModel();
        $admin = $userModel->where('email', $username)
                           ->where('role', 'admin')
                           ->first();

        // On vérifie avec password_verify car le mot de passe est haché dans la BDD (password)
        if ($admin && password_verify($password, $admin['password'])) {
            
            $ses_data = [
                'id'              => $admin['id'],
                'username'        => $admin['nom'] . ' ' . $admin['prenom'],
                'email'           => $admin['email'],
                'isAdminLoggedIn' => true
            ];
            $session->set($ses_data);
            
            return redirect()->to('/admin/dashboard');

        } else {
            $session->setFlashdata('error', 'Email ou mot de passe incorrect, ou accès non autorisé.');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        
        return redirect()->to('/admin/login');
    }
}