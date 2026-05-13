CREATE USER 'dev1'@'localhost' IDENTIFIED BY 'dev';
GRANT ALL PRIVILEGES ON regimeAlimentaire.* TO 'dev1'@'localhost';
FLUSH PRIVILEGES;