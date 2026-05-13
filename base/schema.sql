CREATE DATABASE regimeAlimentaire;
USE regimeAlimentaire;
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    genre ENUM('H', 'F'),
    date_naissance DATE,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE age_ranges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    age_min INT,
    age_max INT
);
CREATE TABLE recommendations_weight (
    id INT PRIMARY KEY AUTO_INCREMENT,
    age_range_id INT,
    genre ENUM('H', 'F'),
    imc_min DECIMAL(4, 2),
    imc_max DECIMAL(4, 2),
    poids_min DECIMAL(5, 2),
    poids_max DECIMAL(5, 2),
    FOREIGN KEY (age_range_id) REFERENCES age_ranges(id)
);
CREATE TABLE health_rules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50),
    -- IMC, poids, calories
    age_min INT,
    age_max INT,
    genre ENUM('H', 'F'),
    valeur_min DECIMAL(5, 2),
    valeur_max DECIMAL(5, 2)
);
CREATE TABLE user_health (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    taille DECIMAL(5, 2),
    -- en mètre
    poids DECIMAL(5, 2),
    -- en kg
    imc DECIMAL(5, 2),
    date_mesure DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE objectifs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) -- perte, prise, maintien
);
CREATE TABLE user_objectifs (
    user_id INT,
    objectif_id INT,
    PRIMARY KEY (user_id, objectif_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
);
CREATE TABLE type_regimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    pourcentage DECIMAL(10, 2)
);
CREATE TABLE regime_user(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    type_regime_id INT,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (type_regime_id) REFERENCES type_regimes(id)
);

CREATE TABLE regimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    description TEXT,
    regime_user_id INT,
    calories INT,
    prix DECIMAL(10, 2),
    duree_jours INT,
    variation_poids DECIMAL(5, 2) DEFAULT 0,
    pourcentage_viande INT DEFAULT 0,
    pourcentage_poisson INT DEFAULT 0,
    pourcentage_volaille INT DEFAULT 0,
    CONSTRAINT id_regime_user FOREIGN KEY (regime_user_id) REFERENCES regime_user(id)
);

CREATE TABLE activites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    description TEXT,
    calories_brulees INT,
    duree_minutes INT
);

CREATE TABLE recommendations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    regime_id INT,
    activite_id INT,
    date_debut DATE,
    date_fin DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id)
);

CREATE TABLE wallet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    solde DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- codes 
CREATE TABLE codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE,
    valeur DECIMAL(10, 2),
    utilise BOOLEAN DEFAULT FALSE
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    code_id INT,
    montant DECIMAL(10, 2),
    date_transaction DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (code_id) REFERENCES codes(id)
);

CREATE TABLE abonnements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('FREE', 'GOLD'),
    date_debut DATE,
    date_fin DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE imc_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    min_value DECIMAL(4, 2),
    max_value DECIMAL(4, 2),
    label VARCHAR(50)
);