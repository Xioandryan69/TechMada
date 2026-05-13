<?php

namespace App\Models;

use CodeIgniter\Model;

class RecommendationModel extends Model
{
    protected $table = 'recommendations';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'regime_id',
        'activite_id',
        'date_debut',
        'date_fin'
    ];

    /**
     * Validation
     */
    protected $validationRules = [

        'user_id' => 'required|integer',

        'regime_id' => 'required|integer',

        'activite_id' => 'permit_empty|integer',

        'date_debut' => 'required|valid_date',

        'date_fin' => 'required|valid_date'
    ];

    /**
     * Enregistrer recommandations utilisateur
     */
    public function enregistrer(
        int $user_id,
        array $regimes,
        array $activites = []
    ): bool {

        // supprimer anciennes recommandations
        $this->where('user_id', $user_id)->delete();

        $date_debut = date('Y-m-d');

        foreach ($regimes as $index => $regime) {

            $activite = null;

            // rotation activités
            if (!empty($activites)) {

                $activite =
                    $activites[
                        $index % count($activites)
                    ];
            }

            // durée du régime
            $duree =
                $regime['duree_jours'] ?? 30;

            $date_fin = date(
                'Y-m-d',
                strtotime("+{$duree} days")
            );

            $this->insert([

                'user_id' => $user_id,

                'regime_id' =>
                    $regime['id'],

                'activite_id' =>
                    $activite['id'] ?? null,

                'date_debut' =>
                    $date_debut,

                'date_fin' =>
                    $date_fin
            ]);
        }

        return true;
    }

    /**
     * Recommandations détaillées utilisateur
     */
    public function getAvecDetails(
        int $user_id
    ): array {

        return $this->db
            ->table('recommendations rec')

            ->select('
                rec.id,
                rec.date_debut,
                rec.date_fin,

                r.id AS regime_id,
                r.nom AS regime_nom,
                r.description AS regime_description,
                r.calories,
                r.prix,
                r.duree_jours,

                tr.nom AS type_regime,

                a.id AS activite_id,
                a.nom AS activite_nom,
                a.description AS activite_description,
                a.calories_brulees,
                a.duree_minutes
            ')

            ->join(
                'regimes r',
                'r.id = rec.regime_id',
                'left'
            )

            ->join(
                'regime_user ru',
                'ru.id = r.regime_user_id',
                'left'
            )

            ->join(
                'type_regimes tr',
                'tr.id = ru.type_regime_id',
                'left'
            )

            ->join(
                'activites a',
                'a.id = rec.activite_id',
                'left'
            )

            ->where('rec.user_id', $user_id)

            ->orderBy(
                'rec.date_debut',
                'DESC'
            )

            ->get()

            ->getResultArray();
    }

    /**
     * Supprimer recommandations utilisateur
     */
    public function supprimerParUser(
        int $user_id
    ): bool {

        return $this
            ->where('user_id', $user_id)
            ->delete();
    }

    /**
     * Recommandations actives
     */
    public function getActives(
        int $user_id
    ): array {

        $today = date('Y-m-d');

        return $this->where(
                'user_id',
                $user_id
            )

            ->where(
                'date_fin >=',
                $today
            )

            ->findAll();
    }

    public function getSuggestionContext(int $userId): array
    {
        $user = $this->db->table('users')
            ->select('id, nom, prenom, email, genre, date_naissance')
            ->where('id', $userId)
            ->get()
            ->getRowArray();

        $health = $this->db->table('user_health')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $objectifRow = $this->db->table('user_objectifs')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (empty($user) || empty($health) || empty($objectifRow)) {
            return [];
        }

        $objectif = $this->db->table('objectifs')
            ->where('id', (int) $objectifRow['objectif_id'])
            ->get()
            ->getRowArray();

        $age = $this->calculerAge($user['date_naissance'] ?? null);
        $ageRange = $this->db->table('age_ranges')
            ->where('age_min <=', $age)
            ->where('age_max >=', $age)
            ->get()
            ->getRowArray();

        $healthRuleQuery = $this->db->table('health_rules')
            ->where('type', 'IMC')
            ->where('genre', $user['genre']);

        if (!empty($ageRange)) {
            $healthRuleQuery->where('age_min', $ageRange['age_min'])
                ->where('age_max', $ageRange['age_max']);
        } else {
            $healthRuleQuery->where('age_min <=', $age)
                ->where('age_max >=', $age);
        }

        $healthRule = $healthRuleQuery->get()->getRowArray();

        $weightRule = null;
        if (!empty($ageRange)) {
            $weightRule = $this->db->table('recommendations_weight')
                ->where('age_range_id', (int) $ageRange['id'])
                ->where('genre', $user['genre'])
                ->get()
                ->getRowArray();
        }

        $currentImc = isset($health['imc']) && $health['imc'] !== null
            ? (float) $health['imc']
            : $this->calculerIMC((float) ($health['poids'] ?? 0), (float) ($health['taille'] ?? 0));

        $currentWeight = (float) ($health['poids'] ?? 0);
        $currentHeight = (float) ($health['taille'] ?? 0);

        return [
            'user' => $user,
            'health' => $health,
            'age' => $age,
            'ageRange' => $ageRange,
            'healthRule' => $healthRule,
            'weightRule' => $weightRule,
            'objectif' => $objectif,
            'objectifType' => $this->resolveObjectiveType($objectif['nom'] ?? ''),
            'currentImc' => $currentImc,
            'currentWeight' => $currentWeight,
            'currentHeight' => $currentHeight,
            'targetImcMin' => $healthRule['valeur_min'] ?? null,
            'targetImcMax' => $healthRule['valeur_max'] ?? null,
            'targetWeightMin' => $weightRule['poids_min'] ?? null,
            'targetWeightMax' => $weightRule['poids_max'] ?? null,
            'targetWeightCenter' => !empty($weightRule)
                ? round(((float) $weightRule['poids_min'] + (float) $weightRule['poids_max']) / 2, 2)
                : null,
        ];
    }

    public function getSuggestionPreview(int $userId): array
    {
        $context = $this->getSuggestionContext($userId);

        if (empty($context)) {
            return [
                'success' => false,
                'message' => 'Impossible de préparer les suggestions.',
                'context' => [],
                'regimes' => [],
                'activites' => [],
            ];
        }

        $regimes = $this->selectRegimesForContext($context);
        $activites = $this->selectActivitesForContext($context, count($regimes));

        return [
            'success' => !empty($regimes),
            'message' => empty($regimes)
                ? 'Aucun régime compatible trouvé pour le moment.'
                : 'Suggestions prêtes.',
            'context' => $context,
            'regimes' => $regimes,
            'activites' => $activites,
        ];
    }

    public function genererPourUtilisateur(int $userId): array
    {
        $preview = $this->getSuggestionPreview($userId);

        if (empty($preview['success']) || empty($preview['regimes'])) {
            return $preview;
        }

        $this->enregistrer(
            $userId,
            $preview['regimes'],
            $preview['activites']
        );

        $preview['message'] = 'Recommandations générées et enregistrées.';

        return $preview;
    }

    public function getRecommendedActivitiesTable(int $userId): array
    {
        return $this->db->table('users u')
            ->select(' 
                rec.id AS recommendation_id,
                rec.date_debut,
                rec.date_fin,
                o.nom AS objectif_nom,
                r.nom AS regime_nom,
                a.nom AS activite_nom,
                a.duree_minutes,
                a.calories_brulees,
                ar.age_min,
                ar.age_max,
                hr.valeur_min AS imc_cible_min,
                hr.valeur_max AS imc_cible_max,
                rw.poids_min AS poids_recommande_min,
                rw.poids_max AS poids_recommande_max
            ')
            ->join('user_objectifs uo', 'uo.user_id = u.id', 'left')
            ->join('objectifs o', 'o.id = uo.objectif_id', 'left')
            ->join('recommendations rec', 'rec.user_id = u.id', 'left')
            ->join('regimes r', 'r.id = rec.regime_id', 'left')
            ->join('activites a', 'a.id = rec.activite_id', 'left')
            ->join('age_ranges ar', 'TIMESTAMPDIFF(YEAR, u.date_naissance, CURDATE()) BETWEEN ar.age_min AND ar.age_max', 'left')
            ->join('health_rules hr', "hr.type = 'IMC' AND hr.genre = u.genre AND hr.age_min = ar.age_min AND hr.age_max = ar.age_max", 'left')
            ->join('recommendations_weight rw', 'rw.age_range_id = ar.id AND rw.genre = u.genre', 'left')
            ->where('u.id', $userId)
            ->where('rec.activite_id IS NOT NULL', null, false)
            ->orderBy('rec.date_debut', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function selectRegimesForContext(array $context): array
    {
        $regimes = $this->db->table('regimes')
            ->select('id, nom, description, calories, prix, duree_jours')
            ->orderBy(
                'calories',
                $context['objectifType'] === 'gain' ? 'DESC' : 'ASC'
            )
            ->get()
            ->getResultArray();

        $selected = [];

        foreach ($regimes as $regime) {
            $variation = $this->resolveVariationPoids($regime);

            if (!$this->matchesObjectiveVariation($context['objectifType'], $variation)) {
                continue;
            }

            $regime['variation_poids'] = round($variation, 2);
            $regime['score'] = $this->scoreRegime($context['objectifType'], $regime, $context);
            $selected[] = $regime;
        }

        usort($selected, static function (array $left, array $right): int {
            return $left['score'] <=> $right['score'];
        });

        return array_slice($selected, 0, 3);
    }

    private function selectActivitesForContext(array $context, int $limit): array
    {
        $activities = $this->db->table('activites')
            ->select('id, nom, description, calories_brulees, duree_minutes')
            ->orderBy(
                'calories_brulees',
                $context['objectifType'] === 'gain' ? 'ASC' : 'DESC'
            )
            ->get()
            ->getResultArray();

        if ($context['objectifType'] === 'maintien') {
            usort($activities, static function (array $left, array $right): int {
                $leftDistance = abs(((int) $left['calories_brulees']) - 300);
                $rightDistance = abs(((int) $right['calories_brulees']) - 300);

                return $leftDistance <=> $rightDistance;
            });
        }

        return array_slice($activities, 0, max(1, $limit));
    }

    private function scoreRegime(string $objectifType, array $regime, array $context): float
    {
        $calories = (float) ($regime['calories'] ?? 0);
        $variation = abs((float) ($regime['variation_poids'] ?? 0));
        $targetWeightCenter = (float) ($context['targetWeightCenter'] ?? 0);
        $currentWeight = (float) ($context['currentWeight'] ?? 0);
        $weightGap = $targetWeightCenter > 0 ? abs($currentWeight - $targetWeightCenter) : 0;

        if ($objectifType === 'gain') {
            return (10000 - $calories) + $variation + $weightGap;
        }

        if ($objectifType === 'maintien') {
            return abs($calories - 2000) + $variation + $weightGap;
        }

        return $calories + $variation + $weightGap;
    }

    private function matchesObjectiveVariation(string $objectifType, float $variation): bool
    {
        if ($objectifType === 'gain') {
            return $variation > 0;
        }

        if ($objectifType === 'maintien') {
            return abs($variation) <= 0.5;
        }

        return $variation < 0;
    }

    private function resolveVariationPoids(array $regime): float
    {
        if (isset($regime['variation_poids']) && $regime['variation_poids'] !== null) {
            return (float) $regime['variation_poids'];
        }

        $text = strtolower((string) ($regime['nom'] ?? '') . ' ' . (string) ($regime['description'] ?? ''));
        $calories = (float) ($regime['calories'] ?? 0);

        if (str_contains($text, 'prise') || str_contains($text, 'masse') || str_contains($text, 'reprise') || str_contains($text, 'augment')) {
            return 2.5;
        }

        if (str_contains($text, 'perte') || str_contains($text, 'détox') || str_contains($text, 'detox') || str_contains($text, 'affin')) {
            return -2.0;
        }

        if (str_contains($text, 'maintien') || str_contains($text, 'équilibr') || str_contains($text, 'equilibr') || str_contains($text, 'imc cible')) {
            return 0.0;
        }

        if ($calories >= 2500) {
            return 2.0;
        }

        if ($calories <= 1700) {
            return -1.5;
        }

        return 0.0;
    }

    private function resolveObjectiveType(string $objectifNom): string
    {
        $objectifNom = strtolower(trim($objectifNom));

        if (str_contains($objectifNom, 'prise') || str_contains($objectifNom, 'augment')) {
            return 'gain';
        }

        if (str_contains($objectifNom, 'maintien') || str_contains($objectifNom, 'imc')) {
            return 'maintien';
        }

        return 'reduce';
    }

    private function calculerAge(?string $dateNaissance): int
    {
        if (empty($dateNaissance)) {
            return 0;
        }

        try {
            $naissance = new \DateTime($dateNaissance);
            $today = new \DateTime();

            return (int) $naissance->diff($today)->y;
        } catch (\Throwable) {
            return 0;
        }
    }
}