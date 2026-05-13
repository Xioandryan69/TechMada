<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class EmployeeController extends BaseController
{
    public function index()
    {
        $model = new EmployeeModel();

        return view('employe/list', [
            'employees' => $model->getWithDepartment()
        ]);
    }

    public function profil()
    {
        $model = new EmployeeModel();

        return view('employe/profile', [
            'user' => $model->find(session()->get('user_id'))
        ]);
    }
}