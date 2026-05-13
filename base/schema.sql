PRAGMA foreign_keys = ON;

  
-- TABLE ROLES
  
CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

  


  
-- TABLE TYPE_CONGES
  
CREATE TABLE type_conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

  
-- TABLE DEPARTEMENTS
  
CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    description TEXT,
    libelle TEXT NOT NULL,

    jours_annuel INTEGER NOT NULL,

    deductible INTEGER NOT NULL DEFAULT 0
);

-- TABLE EMPLOYES
  
CREATE TABLE employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role_id INTEGER NOT NULL,
    departement_id INTEGER NOT NULL,
    date_embauche DATE NOT NULL,
    actif INTEGER NOT NULL DEFAULT 1,

    FOREIGN KEY (role_id)
        REFERENCES roles(id),

    FOREIGN KEY (departement_id)
        REFERENCES departements(id)
);
-- TABLES soldes
CREATE TABLE soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,

    annee INTEGER NOT NULL,

    jours_attribues INTEGER NOT NULL
        CHECK(jours_attribues >= 0),

    jours_pris INTEGER NOT NULL DEFAULT 0
        CHECK(jours_pris >= 0),

    CHECK(jours_pris <= jours_attribues),

    UNIQUE(employe_id, type_conge_id, annee),

    FOREIGN KEY (employe_id)
        REFERENCES employes(id),

    FOREIGN KEY (type_conge_id)
        REFERENCES type_conges(id)
);
-- TABLE CONGES
  
CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,

    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,

    nb_jours INTEGER NOT NULL
        CHECK(nb_jours > 0),

    motif TEXT,

    statut TEXT NOT NULL DEFAULT 'en_attente'
        CHECK(statut IN (
            'en_attente',
            'valide',
            'refuse'
        )),

    commentaire_rh TEXT,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    traite_par INTEGER,

    CHECK(date_fin >= date_debut),

    FOREIGN KEY (employe_id)
        REFERENCES employes(id),

    FOREIGN KEY (type_conge_id)
        REFERENCES type_conges(id),

    FOREIGN KEY (traite_par)
        REFERENCES employes(id)
);


