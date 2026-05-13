<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserHealthModel extends Model
{
    protected $table = 'user_health';
    protected $allowedFields = [
        'user_id', 'taille', 'poids', 'imc', 'imc_calcule', 'date_mesure'
    ];
    protected $returnType = 'array';

    /**
     * Calcule l'IMC : poids (kg) / taille² (m)
     */
    public function calculerIMC(float $poids, float $taille_m): float
    {
        return round($poids / ($taille_m ** 2), 2);
    }

    /**
     * Retourne la catégorie IMC avec couleur CSS
     */
    public function categorieIMC(float $imc): array
    {
        if ($imc < 18.5) {
            return [
                'label'   => 'Insuffisance',
                'couleur' => 'info',
                'conseil' => 'Vous êtes en dessous du poids normal. Pensez à augmenter votre apport calorique.'
            ];
        } elseif ($imc < 25) {
            return [
                'label'   => 'Normal',
                'couleur' => 'success',
                'conseil' => 'Votre IMC est dans la plage normale. Continuez ainsi !'
            ];
        } elseif ($imc < 30) {
            return [
                'label'   => 'Surpoids',
                'couleur' => 'warning',
                'conseil' => 'Un régime équilibré et de l\'exercice peuvent vous aider.'
            ];
        } else {
            return [
                'label'   => 'Obésité',
                'couleur' => 'danger',
                'conseil' => 'Consultez un médecin pour un suivi personnalisé.'
            ];
        }
    }
        /**
     * Récupère les infos santé d'un utilisateur
     */
    public function getParUser(int $user_id): ?array
    {
        return $this->where('user_id', $user_id)->first();
    }
 
    /**
     * Crée ou met à jour les infos santé d'un utilisateur
     */
    public function sauvegarder(int $user_id, array $data): bool
    {
        $existant = $this->getParUser($user_id);
 
        $data['user_id'] = $user_id;
 
        // Recalculer l'IMC si taille/poids sont fournis
        if (!empty($data['poids']) && !empty($data['taille'])) {
            $imc = $this->calculerIMC((float) $data['poids'], (float) $data['taille']);
            $data['imc'] = $imc;

            if ($this->db->fieldExists('imc_calcule', $this->table)) {
                $data['imc_calcule'] = $imc;
            }
        }
 
        if ($existant) {
            return $this->update($existant['id'], $data);
        } else {
            return $this->insert($data) !== false;
        }
    }



}