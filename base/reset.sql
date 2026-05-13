USE regimeAlimentaire;

-- =========================
-- DESACTIVER FOREIGN KEYS
-- =========================
SET FOREIGN_KEY_CHECKS = 0;

-- =========================
-- VIDER LES TABLES
-- =========================

TRUNCATE TABLE recommendations;
TRUNCATE TABLE transactions;
TRUNCATE TABLE abonnements;
TRUNCATE TABLE wallet;

TRUNCATE TABLE user_health;
TRUNCATE TABLE user_objectifs;

TRUNCATE TABLE regimes;
TRUNCATE TABLE regime_user;

TRUNCATE TABLE recommendations_weight;

TRUNCATE TABLE activites;
TRUNCATE TABLE codes;

TRUNCATE TABLE objectifs;
TRUNCATE TABLE type_regimes;

TRUNCATE TABLE health_rules;
TRUNCATE TABLE age_ranges;

TRUNCATE TABLE imc_categories;

TRUNCATE TABLE users;

-- =========================
-- REINITIALISER AUTO_INCREMENT
-- =========================

ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE age_ranges AUTO_INCREMENT = 1;
ALTER TABLE recommendations_weight AUTO_INCREMENT = 1;
ALTER TABLE health_rules AUTO_INCREMENT = 1;
ALTER TABLE user_health AUTO_INCREMENT = 1;
ALTER TABLE objectifs AUTO_INCREMENT = 1;
ALTER TABLE type_regimes AUTO_INCREMENT = 1;
ALTER TABLE regime_user AUTO_INCREMENT = 1;
ALTER TABLE regimes AUTO_INCREMENT = 1;
ALTER TABLE activites AUTO_INCREMENT = 1;
ALTER TABLE recommendations AUTO_INCREMENT = 1;
ALTER TABLE wallet AUTO_INCREMENT = 1;
ALTER TABLE codes AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 1;
ALTER TABLE abonnements AUTO_INCREMENT = 1;
ALTER TABLE imc_categories AUTO_INCREMENT = 1;

-- =========================
-- REACTIVER FOREIGN KEYS
-- =========================
SET FOREIGN_KEY_CHECKS = 1;