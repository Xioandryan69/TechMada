PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS conges;
DROP TABLE IF EXISTS soldes;
DROP TABLE IF EXISTS employes;
DROP TABLE IF EXISTS type_conges;
DROP TABLE IF EXISTS departements;
DROP TABLE IF EXISTS roles;

DELETE FROM sqlite_sequence;

PRAGMA foreign_keys = ON;