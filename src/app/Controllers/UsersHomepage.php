<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\RecommendationModel;
use App\Models\UserHealthModel;
use App\Models\UserObjectifModel;

class UsersHomepage extends BaseController
{
    public function imc()
    {
        $userId = (int) session()->get('id');
        $userHealthModel = new UserHealthModel();

        $sante = $userHealthModel->getParUser($userId);
        $imc = 0;
        $categorie = null;

        if ($sante && isset($sante['imc']) && $sante['imc'] !== null) {
            $imc = (float) $sante['imc'];
            $categorie = $userHealthModel->categorieIMC($imc);
        }

        return view('users/imc/index', [
            'sante' => $sante,
            'imc' => round($imc, 2),
            'categorie' => $categorie,
        ]);
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $objectifModel = new ObjectifModel();
        $userObjectifModel = new UserObjectifModel();
        $userId = (int) session()->get('id');
        
        $userNameQuery = "SELECT nom FROM users WHERE id = " . $userId;
        $userName = $db->query($userNameQuery)->getRow()->nom ?? 'Utilisateur';

        $userLastNameQuery = "SELECT prenom FROM users WHERE id = " . $userId;
        $userLastName = $db->query($userLastNameQuery)->getRow()->prenom ?? '';

        $userMailQuery = "SELECT email FROM users WHERE id = " . $userId;
        $userMail = $db->query($userMailQuery)->getRow()->email ?? '';

        $userdateNaissanceQuery = "SELECT date_naissance FROM users WHERE id = " . $userId;
        $dateNaissance = $db->query($userdateNaissanceQuery)->getRow()->date_naissance ?? '';
        $dateNaissance2 = new \DateTime($dateNaissance);
        $currentDate = new \DateTime();
        $userAge = $dateNaissance2->diff($currentDate)->y;

        $userTailleQuery = "SELECT taille FROM user_health WHERE user_id = " . $userId;
        $userTaille = $db->query($userTailleQuery)->getRow()->taille ?? '';

        $userPoidsQuery = "SELECT poids FROM user_health WHERE user_id = " . $userId;
        $userPoids = $db->query($userPoidsQuery)->getRow()->poids ?? 0;

        $userTailleMeters = (float)$userTaille;
        $userImc = ($userTailleMeters > 0) ? ($userPoids / ($userTailleMeters * $userTailleMeters)) : 0;

        $objectifs = $objectifModel->findAll();
        $userObjectifRow = $userObjectifModel->where('user_id', $userId)->first();
        $objectifId = $userObjectifRow ? (int) $userObjectifRow['objectif_id'] : 0;
        $objectifActuel = $objectifId > 0 ? $objectifModel->find($objectifId) : null;
        $userObjectif = $objectifActuel['nom'] ?? '';

        // Récupération des régimes et activités (recommandations suivies)
        $userActivitesRegimesQuery = "
            SELECT r.nom as regime_nom, a.nom as activite_nom, rec.date_debut, rec.date_fin 
            FROM recommendations rec
            LEFT JOIN regimes r ON rec.regime_id = r.id
            LEFT JOIN activites a ON rec.activite_id = a.id
            WHERE rec.user_id = " . $userId . "
            ORDER BY rec.date_debut DESC";
        $userProgrammes = $db->query($userActivitesRegimesQuery)->getResultArray();

        $data = [
            'userName'        => $userName,
            'userLastName'    => $userLastName,
            'userMail'         => $userMail,
            'userAge'          => $userAge,
            'userTaille'       => $userTaille,
            'userPoids'        => $userPoids,
            'userImc'       => round($userImc, 2),
            'userObjectif' => $userObjectif,
            'currentObjectifId' => $objectifId,
            'objectifs' => $objectifs,
            'userProgrammes' => $userProgrammes,
            'dateNaissance' => $dateNaissance
        ];

        return view('users/homepage', $data);
    }

    // -- Mise à jour du profil (Ages, infos, Poids, etc.)
    public function updateProfile()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');
        $userHealthModel = new UserHealthModel();

        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');
        $date_naissance = $this->request->getPost('date_naissance');

        // Met à jour la table users
        $db->table('users')->where('id', $userId)->update([
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $date_naissance
        ]);

        // Sauvegarde santé via le modèle (IMC recalculé automatiquement)
        $userHealthModel->sauvegarder((int) $userId, [
            'taille' => (float) $taille,
            'poids' => (float) $poids,
            'date_mesure' => date('Y-m-d H:i:s'),
        ]);

        // MAJ de la session si le nom change
        session()->set('username', $nom . ' ' . $prenom);
        
        session()->setFlashdata('success', 'Votre profil a été mis à jour avec succès.');
        return redirect()->to('users/homepage');
    }

    // -- Choix ou mise à jour de l'objectif
    public function updateObjectif()
    {
        $userId = (int) session()->get('id');
        $objId = (int) $this->request->getPost('objectif_id');
        $objectifModel = new ObjectifModel();
        $userObjectifModel = new UserObjectifModel();

        if ($userId <= 0) {
            session()->setFlashdata('error', 'Session utilisateur invalide.');
            return redirect()->to('users/login');
        }

        if ($objId <= 0 || !$objectifModel->find($objId)) {
            session()->setFlashdata('error', 'Objectif invalide.');
            return redirect()->to('users/homepage');
        }

        if (!$userObjectifModel->sauvegarder($userId, $objId)) {
            session()->setFlashdata('error', 'Impossible de mettre à jour votre objectif.');
            return redirect()->to('users/homepage');
        }

        session()->setFlashdata('success', 'Votre objectif a été mis à jour.');
        return redirect()->to('users/homepage');
    }

    public function recommendations()
    {
        $userId = (int) session()->get('id');
        $recommendationModel = new RecommendationModel();

        $preview = $recommendationModel->getSuggestionPreview($userId);
        $savedRecommendations = $recommendationModel->getAvecDetails($userId);

        return view('users/recommendations/index', array_merge($preview, [
            'savedRecommendations' => $savedRecommendations,
        ]));
    }

    public function generateRecommendations()
    {
        $userId = (int) session()->get('id');
        $recommendationModel = new RecommendationModel();
        $result = $recommendationModel->genererPourUtilisateur($userId);

        session()->setFlashdata(
            !empty($result['success']) ? 'success' : 'error',
            $result['message'] ?? 'Impossible de générer les recommandations.'
        );

        return redirect()->to('users/recommendations');
    }

    public function recommendedActivities()
    {
        $userId = (int) session()->get('id');
        $recommendationModel = new RecommendationModel();

        $context = $recommendationModel->getSuggestionContext($userId);
        $activitiesTable = $recommendationModel->getRecommendedActivitiesTable($userId);

        return view('users/recommendations/activities', [
            'context' => $context,
            'activitiesTable' => $activitiesTable,
        ]);
    }

    public function exportPdf()
    {
        // 1. Inclure FPDF (chemin depuis public/index.php normalement, ou via ROOTPATH)
        require_once ROOTPATH . 'fpdf186/fpdf.php';

        $userId = (int) session()->get('id');
        $db = \Config\Database::connect();
        
        $userName = session()->get('username') ?? 'Utilisateur';

        // Utilisation du modèle fait par les développeurs pour récupérer les recommandations
        $recommendationModel = new \App\Models\RecommendationModel();
        
        // Soit vous exportez les suggestions (preview), soit les sauvegardées :
        // Ici on prend celles qui sont sauvegardées/activées.
        $savedRecommendations = $recommendationModel->getAvecDetails($userId);

        // 2. Création du PDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        
        // Titre
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('My Régime - Vos Suggestions & Programmes'), 0, 1, 'C');
        $pdf->Ln(10);

        // Informations utilisateur
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Client : ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode($userName), 0, 1);
        $pdf->Ln(10);

        // Programmes
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('Vos Recommandations Enregistrées'), 0, 1);
        $pdf->Ln(5);

        if (empty($savedRecommendations)) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, utf8_decode('Aucune recommandation enregistrée en cours.'), 0, 1);
        } else {
            // En-têtes du tableau
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(60, 10, utf8_decode('Régime'), 1, 0, 'C');
            $pdf->Cell(60, 10, utf8_decode('Activité'), 1, 0, 'C');
            $pdf->Cell(35, 10, utf8_decode('Date Début'), 1, 0, 'C');
            $pdf->Cell(35, 10, utf8_decode('Date Fin'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 10);
            foreach ($savedRecommendations as $prog) {
                $regime = mb_substr(utf8_decode($prog['regime_nom'] ?? 'Aucun régime'), 0, 30);
                $activite = mb_substr(utf8_decode($prog['activite_nom'] ?? 'Aucune activité'), 0, 30);
                $debut = date('d/m/Y', strtotime($prog['date_debut']));
                $fin = date('d/m/Y', strtotime($prog['date_fin']));

                $pdf->Cell(60, 10, $regime, 1, 0);
                $pdf->Cell(60, 10, $activite, 1, 0);
                $pdf->Cell(35, 10, $debut, 1, 0, 'C');
                $pdf->Cell(35, 10, $fin, 1, 1, 'C');
            }
        }

        // Forcer le téléchargement du PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('D', 'Mes_Recommandations_MyRegime.pdf');
        exit;
    }
}