<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('login');
    }

    public function login()
    {
        $model = new EmployeeModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->getByEmail($email);

        $storedPassword = (string) ($user['password'] ?? '');

        if (
            !$user ||
            (!password_verify($password, $storedPassword) && $password !== $storedPassword)
        ) {
            return redirect()->back()->with('error', 'Login invalide');
        }

        session()->set([
            'user_id' => $user['id'],
            'role' => $user['role'],
            'user_name' => trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')),
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function dashboard()
    {
        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'rh') {
            return redirect()->to('/rh/demandes');
        }

        if ($role === 'employe') {
            return redirect()->to('/employee/dashboard');
        }

        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}