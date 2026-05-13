PRAGMA foreign_keys = OFF;

DELETE FROM conges;
DELETE FROM soldes;
DELETE FROM employes;
DELETE FROM type_conges;
DELETE FROM departements;
DELETE FROM roles;

DELETE FROM sqlite_sequence;

PRAGMA foreign_keys = ON;