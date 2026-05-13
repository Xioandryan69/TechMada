<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nom', 'prenom', 'email', 'password', 'role', 
        'departement_id', 'date_embauche', 'actif'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nom'            => 'required|min_length[2]|max_length[100]',
        'prenom'         => 'required|min_length[2]|max_length[100]',
        'email'          => 'required|valid_email|is_unique[employees.email,id,{id}]',
        'password'       => 'required|min_length[6]',
        'role'           => 'required|in_list[employe,rh,admin]',
        'departement_id' => 'required|integer',
        'date_embauche'  => 'required|valid_date',
        'actif'          => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];

    protected $skipValidation = false;

    // Méthodes utiles
    public function getActiveEmployees()
    {
        return $this->where('actif', 1)
                    ->findAll();
    }

    public function getByEmail(string $email)
    {
        return $this->where('email', $email)
                    ->where('actif', 1)
                    ->first();
    }

    public function getWithDepartment()
    {
        return $this->select('employees.*, departements.nom as departement_nom')
                    ->join('departements', 'departements.id = employees.departement_id')
                    ->where('employees.actif', 1)
                    ->findAll();
    }
    public function getByRole(string $role)
{
    return $this->where('role', $role)->findAll();
}
}