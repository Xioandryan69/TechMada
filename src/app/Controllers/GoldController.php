<?php

namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\AbonnementModel;
use App\Models\TransactionModel;

class GoldController extends BaseController
{
    protected $walletModel;
    protected $abonnementModel;
    protected $transactionModel;
    protected $db;

    /**
     * =====================================================
     * CONFIG GOLD
     * =====================================================
     */
    private $goldPrice    = 50000;
    private $goldDiscount = 15;
    private $goldDuration = 30;

    public function __construct()
    {
        $this->walletModel      = new WalletModel();
        $this->abonnementModel  = new AbonnementModel();
        // BUG CORRIGÉ : TransactionModel ajouté pour enregistrer l'achat
        $this->transactionModel = new TransactionModel();
        $this->db               = \Config\Database::connect();
    }

    /**
     * =====================================================
     * PAGE GOLD
     * =====================================================
     */
    public function index()
    {
        $userId = session()->get('id');

        if (!$userId) {
            return redirect()->to('/users/login');
        }

        // BUG CORRIGÉ : getOuCreerParUser évite null → crash dans la vue
        $wallet = $this->walletModel->getOuCreerParUser((int) $userId);

        $goldSubscription = $this->abonnementModel
            ->where('user_id', $userId)
            ->where('type', 'GOLD')
            ->where('date_fin >=', date('Y-m-d'))
            ->orderBy('date_fin', 'DESC')
            ->first();

        $isGold = $goldSubscription ? true : false;

        return view('users/wallet/gold', [
            'wallet'           => $wallet,
            'goldPrice'        => $this->goldPrice,
            'goldDiscount'     => $this->goldDiscount,
            'goldDuration'     => $this->goldDuration,
            'isGold'           => $isGold,
            'goldSubscription' => $goldSubscription
        ]);
    }

    /**
     * =====================================================
     * ACHETER GOLD
     * =====================================================
     */
    public function buy()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
        }

        // Vérifier si déjà GOLD
        $alreadyGold = $this->abonnementModel
            ->where('user_id', $userId)
            ->where('type', 'GOLD')
            ->where('date_fin >=', date('Y-m-d'))
            ->first();

        if ($alreadyGold) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous êtes déjà GOLD'
            ]);
        }

        // BUG CORRIGÉ : getOuCreerParUser évite null
        $wallet = $this->walletModel->getOuCreerParUser((int) $userId);

        // Solde insuffisant
        if ((float) $wallet['solde'] < $this->goldPrice) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant'
            ]);
        }

        // =============================================
        // TRANSACTION SQL
        // =============================================
        $this->db->transStart();

        $newBalance = (float) $wallet['solde'] - $this->goldPrice;

        $this->walletModel->update($wallet['id'], ['solde' => $newBalance]);

        $dateDebut = date('Y-m-d');
        $dateFin   = date('Y-m-d', strtotime("+{$this->goldDuration} days"));

        $this->abonnementModel->insert([
            'user_id'    => $userId,
            'type'       => 'GOLD',
            'date_debut' => $dateDebut,
            'date_fin'   => $dateFin
        ]);

        // BUG CORRIGÉ : enregistrement de la transaction manquant à l'origine
        $this->transactionModel->insert([
            'user_id'          => $userId,
            'code_id'          => null,
            'montant'          => $this->goldPrice,
            'date_transaction' => date('Y-m-d H:i:s')
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur transaction SQL'
            ]);
        }

        return $this->response->setJSON([
            'success'     => true,
            'message'     => 'Abonnement GOLD activé avec succès',
            'new_balance' => number_format($newBalance, 2),
            'date_fin'    => $dateFin
        ]);
    }

    /**
     * =====================================================
     * VERIFIER SI USER GOLD
     * =====================================================
     */
    public function isGold($userId)
    {
        $gold = $this->abonnementModel
            ->where('user_id', $userId)
            ->where('type', 'GOLD')
            ->where('date_fin >=', date('Y-m-d'))
            ->first();

        return $gold ? true : false;
    }

    /**
     * =====================================================
     * CALCULER PRIX GOLD
     * =====================================================
     */
    public function calculatePrice($prix, $userId)
    {
        $isGold = $this->isGold($userId);

        if (!$isGold) {
            return [
                'original_price' => $prix,
                'final_price'    => $prix,
                'discount'       => 0
            ];
        }

        $newPrice = $prix - ($prix * ($this->goldDiscount / 100));

        return [
            'original_price' => $prix,
            'final_price'    => $newPrice,
            'discount'       => $this->goldDiscount
        ];
    }
}