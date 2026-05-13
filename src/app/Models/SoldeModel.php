<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_attribues',
        'jours_pris'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'annee' => 'required|exact_length[4]|numeric',
        'jours_attribues' => 'required|integer|greater_than_equal_to[0]',
        'jours_pris' => 'required|integer|greater_than_equal_to[0]'
    ];

    /**
     * Récupère les soldes d'un employé pour une année donnée
     */
    public function getByEmployeeAndYear(int $employeId, int $annee)
    {
        return $this->select('soldes.*, type_conges.nom as type_nom')
            ->join('type_conges', 'type_conges.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $employeId)
            ->where('soldes.annee', $annee)
            ->findAll();
    }

    /**
     * Mise à jour des jours pris (utilisé lors de l'approbation)
     */
    public function updateJoursPris(int $employeId, int $typeCongeId, int $annee, int $jours)
    {
        return $this->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->set('jours_pris', "jours_pris + {$jours}", false)
            ->update();
    }

    /**
     * Annulation (remise à zéro des jours pris)
     */
    public function rollbackJoursPris(int $employeId, int $typeCongeId, int $annee, int $jours)
    {
        return $this->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->set('jours_pris', "jours_pris - {$jours}", false)
            ->update();
    }

    public function getRemainingDays($employeId, $typeId, $annee)
    {
        $row = $this->where([
            'employe_id' => $employeId,
            'type_conge_id' => $typeId,
            'annee' => $annee
        ])->first();

        if (!$row)
            return 0;

        return $row['jours_attribues'] - $row['jours_pris'];
    }
}