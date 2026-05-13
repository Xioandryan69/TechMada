ALTER TABLE regimes ADD COLUMN variation_poids DECIMAL(5, 2) DEFAULT 0;
ALTER TABLE regimes ADD COLUMN pourcentage_viande INT DEFAULT 0;
ALTER TABLE regimes ADD COLUMN pourcentage_poisson INT DEFAULT 0;
ALTER TABLE regimes ADD COLUMN pourcentage_volaille INT DEFAULT 0;