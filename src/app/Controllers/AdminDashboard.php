<?php

namespace App\Controllers;

class AdminDashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $nbUsers = $db->table('users')->where('role', 'user')->countAllResults();
        
        $revenus = $db->table('transactions')->selectSum('montant')->get()->getRow()->montant ?? 0;
        
        $nbRegimesVendus = $db->table('recommendations')->countAllResults();

        $revenusQuery = "SELECT DATE_FORMAT(date_transaction, '%Y-%m') as mois, SUM(montant) as total FROM transactions GROUP BY mois ORDER BY mois ASC";
        $revenusParMois = $db->query($revenusQuery)->getResultArray();

        $objectifsQuery = "SELECT o.nom, COUNT(uo.user_id) as total FROM objectifs o LEFT JOIN user_objectifs uo ON o.id = uo.objectif_id GROUP BY o.id";
        $objectifsCount = $db->query($objectifsQuery)->getResultArray();

        $data = [
            'nbUsers'         => $nbUsers,
            'revenus'         => $revenus,
            'nbRegimesVendus' => $nbRegimesVendus,
            'revenusParMois'  => json_encode($revenusParMois), // json_encode pour l'utiliser en Javascript
            'objectifsCount'  => json_encode($objectifsCount)
        ];

        return view('admin/dashboard', $data);
    }
}