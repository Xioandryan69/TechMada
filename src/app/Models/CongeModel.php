<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'nb_jours' => 'required|integer|greater_than[0]',
        'motif' => 'required|min_length[10]',
        'statut' => 'required|in_list[en_attente,approuve,refuse,annule]'
    ];

    public function getPendingLeaves()
    {
        return $this->where('statut', 'en_attente')
            ->findAll();
    }

    public function getLeavesByEmployee(int $employeId)
    {
        return $this->where('employe_id', $employeId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getAllWithDetails()
    {
        return $this->select('conges.*, employees.nom, employees.prenom, types_conge.libelle as type_libelle')
            ->join('employees', 'employees.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();
    }
    public function getByStatus(string $status)
    {
        return $this->where('statut', $status)->findAll();
    }
    public function isValidLeave($employeId, $jours)
    {
        // logique solde (sera utilisé dans service)
    }
}