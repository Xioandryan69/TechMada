
--- 
INSERT INTO roles (nom) VALUES
('admin'),
('rh'),
('employe');

INSERT INTO type_conges (nom) VALUES
('conge_annuel'),
('conge_maladie'),
('conge_exceptionnel');

INSERT INTO departements (nom, description, libelle, jours_annuel, deductible) VALUES
('informatique', 'Département IT', 'IT', 30, 0),
('ressources_humaines', 'Gestion du personnel', 'RH', 25, 0),
('finance', 'Gestion financière', 'FIN', 28, 1);

INSERT INTO employes
(nom, prenom, email, password, role_id, departement_id, date_embauche, actif)
VALUES

('Rakoto', 'Jean', 'jean@test.com', '1234', 1, 1, '2024-01-01', 1),

('Rabe', 'Paul', 'paul@test.com', '1234', 2, 2, '2024-02-01', 1),

('Rasoa', 'Marie', 'marie@test.com', '1234', 3, 1, '2024-03-01', 1),

('Andry', 'Lucas', 'lucas@test.com', '1234', 3, 3, '2024-04-01', 1);

INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES
(1, 1, 2025, 30, 5),
(2, 1, 2025, 25, 10),
(3, 1, 2025, 30, 2),
(4, 1, 2025, 28, 0),

(1, 2, 2025, 10, 2),
(2, 2, 2025, 10, 1);

INSERT INTO conges (employe_id, type_conge_id, date_debut, date_fin, nb_jours, motif, statut, commentaire_rh, traite_par) VALUES
(3, 1, '2025-07-01', '2025-07-05', 5, 'Vacances', 'valide', 'Bon repos', 2),
(4, 2, '2025-06-10', '2025-06-12', 3, 'Maladie', 'valide', 'Certificat fourni', 2),
(2, 1, '2025-08-15', '2025-08-20', 6, 'Voyage', 'en_attente', NULL, NULL),
(3, 3, '2025-05-01', '2025-05-01', 1, 'Urgence familiale', 'refuse', 'Non justifié', 1);