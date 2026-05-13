USE regimeAlimentaire;
-- imc_categories
INSERT INTO imc_categories (min_value, max_value, label) VALUES
(0.00,  18.49, 'Insuffisance pondérale'),
(18.50, 24.99, 'Poids normal'),
(25.00, 29.99, 'Surpoids'),
(30.00, 34.99, 'Obésité modérée'),
(35.00, 99.99, 'Obésité sévère');
 
-- age_ranges
INSERT INTO age_ranges (age_min, age_max) VALUES
(15, 17),
(18, 29),
(30, 44),
(45, 59),
(60, 99);
 
-- objectifs
INSERT INTO objectifs (nom) VALUES
('Perte de poids'),
('Prise de poids'),
('Maintien du poids');
 
-- type_regimes (viande / poisson / volaille)
INSERT INTO type_regimes (nom, pourcentage) VALUES
('Viande',   40.00),
('Poisson',  35.00),
('Volaille', 25.00);
 
-- health_rules  (IMC idéal selon âge et genre)
INSERT INTO health_rules (type, age_min, age_max, genre, valeur_min, valeur_max) VALUES
('IMC', 15, 17, 'H', 17.50, 23.00),
('IMC', 15, 17, 'F', 17.00, 22.50),
('IMC', 18, 29, 'H', 18.50, 24.99),
('IMC', 18, 29, 'F', 18.50, 23.99),
('IMC', 30, 44, 'H', 18.50, 24.99),
('IMC', 30, 44, 'F', 18.50, 23.99),
('IMC', 45, 59, 'H', 20.00, 26.00),
('IMC', 45, 59, 'F', 20.00, 25.50),
('IMC', 60, 99, 'H', 21.00, 27.00),
('IMC', 60, 99, 'F', 21.00, 26.50);
 
-- recommendations_weight (poids idéal par tranche d'âge et genre)
INSERT INTO recommendations_weight (age_range_id, genre, imc_min, imc_max, poids_min, poids_max) VALUES
(1, 'H', 17.50, 23.00, 48.00, 70.00),
(1, 'F', 17.00, 22.50, 44.00, 63.00),
(2, 'H', 18.50, 24.99, 55.00, 85.00),
(2, 'F', 18.50, 23.99, 50.00, 75.00),
(3, 'H', 18.50, 24.99, 58.00, 88.00),
(3, 'F', 18.50, 23.99, 52.00, 77.00),
(4, 'H', 20.00, 26.00, 62.00, 92.00),
(4, 'F', 20.00, 25.50, 55.00, 82.00),
(5, 'H', 21.00, 27.00, 65.00, 95.00),
(5, 'F', 21.00, 26.50, 58.00, 86.00);
 
-- users (1 admin + 5 utilisateurs)
-- password_hash de "password" — à remplacer avec password_hash() en PHP
INSERT INTO users (nom, prenom, email, password, genre, date_naissance, role) VALUES
('Admin',        'Super',    'admin@regime.mg',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'H', '1985-01-01', 'admin'),
('Rakoto',       'Gaelle',   'gaelle@test.mg',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'F', '2000-03-15', 'user'),
('Rabe',         'Sarobidy', 'sarobidy@test.mg',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'F', '2001-07-22', 'user'),
('Andria',       'Ny Avo',   'nyavo@test.mg',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'F', '1999-11-08', 'user'),
('Rasoa',        'Marie',    'marie@test.mg',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'F', '2002-05-30', 'user'),
('Rajaonarison', 'Jean',     'jean@test.mg',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'H', '1998-09-12', 'user');
 
-- user_health (taille en cm ici, adapter selon votre choix mètre/cm)
INSERT INTO user_health (user_id, taille, poids, imc) VALUES
(2, 1.65, 72.00, 26.45),  -- Gaelle  : surpoids
(3, 1.58, 45.00, 18.03),  -- Sarobidy: insuffisance pondérale
(4, 1.70, 68.00, 23.53),  -- Ny Avo  : normal
(5, 1.60, 85.00, 33.20),  -- Marie   : obésité modérée
(6, 1.75, 62.00, 20.24);  -- Jean    : normal
 
-- objectifs choisis par les utilisateurs
INSERT INTO user_objectifs (user_id, objectif_id) VALUES
(2, 1),  -- Gaelle  → Perte de poids
(3, 2),  -- Sarobidy→ Prise de poids
(4, 3),  -- Ny Avo  → Maintien
(5, 1),  -- Marie   → Perte de poids
(6, 3);  -- Jean    → Maintien
 
-- regime_user : association utilisateur ↔ type de régime
INSERT INTO regime_user (id_user, type_regime_id) VALUES
(2, 2),  -- Gaelle   → Poisson
(3, 1),  -- Sarobidy → Viande
(4, 3),  -- Ny Avo   → Volaille
(5, 2),  -- Marie    → Poisson
(6, 3);  -- Jean     → Volaille
 
-- regimes (5 régimes)
INSERT INTO regimes (nom, description, regime_user_id, calories, prix, duree_jours, variation_poids) VALUES
(
  'Régime Méditerranéen',
  'Régime équilibré inspiré du bassin méditerranéen. Riche en légumes, poisson et huile d\'olive. Idéal pour une perte de poids progressive.',
  1, 1600, 45000.00, 30, -2.00
),
(
  'Programme Prise de Masse',
  'Apport calorique élevé centré sur les protéines animales et les glucides complexes. À combiner avec la musculation.',
  2, 2800, 60000.00, 45, 3.50
),
(
  'Régime Équilibré IMC Cible',
  'Plan nutritionnel personnalisé pour atteindre et maintenir un IMC dans la fourchette normale (18.5–24.9).',
  3, 2000, 75000.00, 60, 0.00
),
(
  'Cure Détox 21 jours',
  'Programme court axé sur la purification de l\'organisme. Riche en fibres, légumes verts et protéines légères.',
  4, 1400, 35000.00, 21, -1.50
),
(
  'Reprise de Poids Saine',
  'Accompagnement pour les personnes en insuffisance pondérale. Apports denses et nutritifs, bonnes graisses et protéines de qualité.',
  5, 2600, 50000.00, 30, 2.50
);
 
-- activites (5 activités)
INSERT INTO activites (nom, description, calories_brulees, duree_minutes) VALUES
('Marche rapide',        'Activité douce accessible à tous, améliore le cardio et brûle des calories sans risque de blessure.',        250, 45),
('Musculation',          'Entraînement en salle avec charges progressives. Favorise la prise de masse musculaire.',                    400, 60),
('Yoga & Stretching',    'Séance de yoga doux et étirements profonds. Réduit le stress et améliore l\'équilibre corporel.',            150, 45),
('Natation',             'Sport complet mobilisant tout le corps. Très efficace pour brûler des graisses en ménageant les articulations.', 450, 45),
('HIIT',                 'Entraînement fractionné haute intensité. Court et très efficace pour brûler les graisses.',                  500, 30);
 
-- recommendations (suggestions régime + activité par utilisateur)
INSERT INTO recommendations (user_id, regime_id, activite_id, date_debut, date_fin) VALUES
(2, 1, 4, '2026-05-01', '2026-05-31'),  -- Gaelle   : Méditerranéen + Natation
(3, 5, 2, '2026-05-02', '2026-06-01'),  -- Sarobidy : Reprise poids + Musculation
(4, 3, 3, '2026-05-02', '2026-07-01'),  -- Ny Avo   : Équilibré + Yoga
(5, 4, 1, '2026-05-03', '2026-05-24'),  -- Marie    : Détox + Marche
(6, 3, 5, '2026-05-04', '2026-07-03');  -- Jean     : Équilibré + HIIT
 
-- wallet (solde initial par utilisateur)
INSERT INTO wallet (user_id, solde) VALUES
(2, 15000.00),  -- Gaelle
(3,  5000.00),  -- Sarobidy
(4,     0.00),  -- Ny Avo
(5,  8000.00),  -- Marie
(6,  2000.00);  -- Jean
 
-- codes (15 codes de recharge)
INSERT INTO codes (code, valeur, utilise) VALUES
('CODE001', 5000.00, FALSE),
('CODE002', 5000.00, FALSE),
('CODE003', 5000.00, FALSE),
('CODE004', 5000.00, FALSE),
('CODE005', 5000.00, FALSE),
('CODE006', 2000.00, FALSE),
('CODE007', 2000.00, FALSE),
('CODE008', 2000.00, FALSE),
('CODE009', 2000.00, FALSE),
('CODE010', 2000.00, FALSE),
('CODE011',10000.00, FALSE),
('CODE012',10000.00, FALSE),
('CODE013',10000.00, FALSE),
('CODE014', 5000.00, TRUE),   -- déjà utilisé (test back office)
('CODE015', 2000.00, TRUE);   -- déjà utilisé (test back office)
 
-- transactions (liées aux codes déjà utilisés)
INSERT INTO transactions (user_id, code_id, montant, date_transaction) VALUES
(2, 14, 5000.00, '2026-04-30 10:22:00'),
(5, 15, 2000.00, '2026-05-01 14:05:00');
 
-- abonnements
INSERT INTO abonnements (user_id, type, date_debut, date_fin) VALUES
(2, 'FREE', '2026-05-01', NULL),
(3, 'GOLD', '2026-05-02', '2027-05-02'),  -- Sarobidy a l'option Gold
(4, 'FREE', '2026-05-02', NULL),
(5, 'FREE', '2026-05-03', NULL),
(6, 'FREE', '2026-05-04', NULL);
 
