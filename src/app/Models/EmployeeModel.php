<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nom', 'prenom', 'email', 'password', 'role_id',
        'departement_id', 'date_embauche', 'actif'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nom'            => 'required|min_length[2]|max_length[100]',
        'prenom'         => 'required|min_length[2]|max_length[100]',
        'email'          => 'required|valid_email|is_unique[employes.email,id,{id}]',
        'password'       => 'required|min_length[6]',
        'role_id'        => 'required|integer',
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
        return $this->select('employes.*, roles.nom as role')
                    ->join('roles', 'roles.id = employes.role_id')
                    ->where('employes.email', $email)
                    ->where('employes.actif', 1)
                    ->first();
    }

    public function getWithDepartment()
    {
        return $this->select('employes.*, departements.libelle as departement_nom, roles.nom as role')
                    ->join('departements', 'departements.id = employes.departement_id')
                    ->join('roles', 'roles.id = employes.role_id')
                    ->where('employes.actif', 1)
                    ->findAll();
    }
    public function getByRole(string $role)
{
    return $this->select('employes.*, roles.nom as role')
                ->join('roles', 'roles.id = employes.role_id')
                ->where('roles.nom', $role)
                ->findAll();
}
}