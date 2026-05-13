<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $model = new EmployeeModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Login invalide');
        }

        session()->set([
            'user_id' => $user['id'],
            'role' => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}