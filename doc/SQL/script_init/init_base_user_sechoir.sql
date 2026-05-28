DROP DATABASE IF EXISTS base_sechoir;

CREATE DATABASE base_sechoir
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE base_sechoir;

-- =========================
-- TABLE MASSE
-- =========================
CREATE TABLE masse(
   id_masse INT AUTO_INCREMENT,
   masse_masse DECIMAL(4,1) NOT NULL,
   masse_dateHeure DATETIME NOT NULL,
   PRIMARY KEY(id_masse)
) ENGINE=InnoDB;

-- =========================
-- TABLE VARIETE
-- =========================
CREATE TABLE variete(
   id_variete INT AUTO_INCREMENT,
   variete_nom VARCHAR(32) NOT NULL,
   variete_dateHeureCreation DATETIME NOT NULL,
   variete_actif BOOLEAN NOT NULL,
   PRIMARY KEY(id_variete)
) ENGINE=InnoDB;

-- =========================
-- TABLE ETAGE
-- =========================
CREATE TABLE etage(
   id_etage INT AUTO_INCREMENT,
   PRIMARY KEY(id_etage)
) ENGINE=InnoDB;

-- =========================
-- TABLE CAPTEUR
-- =========================
CREATE TABLE capteur(
   adresse_capteur VARCHAR(32) NOT NULL,
   capteur_nom VARCHAR(32) NOT NULL,
   capteur_gpio VARCHAR(8) NOT NULL,
   capteur_actif BOOLEAN NOT NULL,
   PRIMARY KEY(adresse_capteur)
) ENGINE=InnoDB;

-- =========================
-- TABLE ETAT SECHOIR
-- =========================
CREATE TABLE etatSechoir(
   id_etatSechoir INT AUTO_INCREMENT,
   etatSechoir_status VARCHAR(32),
   etatSechoir_dataMaj DATETIME,
   etatSechoir_pauseDebut DATETIME,
   etatSechoir_ajoutMinute SMALLINT,
   etatSechoir_seuilMin DECIMAL(4,1),
   etatSechoir_seuilMax DECIMAL(4,1),
   PRIMARY KEY(id_etatSechoir)
) ENGINE=InnoDB;

-- =========================
-- TABLE EVENEMENT
-- =========================
CREATE TABLE evenement(
   id_event INT AUTO_INCREMENT,
   event_type VARCHAR(64) NOT NULL,
   event_description VARCHAR(2048) NOT NULL,
   event_dateHeureDebut DATETIME NOT NULL,
   event_dateHeureFin DATETIME,
   PRIMARY KEY(id_event)
) ENGINE=InnoDB;

-- =========================
-- TABLE LOT
-- =========================
CREATE TABLE lot(
   id_lot INT AUTO_INCREMENT,
   lot_remplissage TINYINT NOT NULL,
   lot_dateHeureEntree DATETIME NOT NULL,
   lot_dateHeureSortie DATETIME,
   lot_dureeTheorique INT NOT NULL,
   lot_actif BOOLEAN NOT NULL,
   id_masse INT,
   id_variete INT NOT NULL,
   PRIMARY KEY(id_lot),
   FOREIGN KEY(id_masse) REFERENCES masse(id_masse),
   FOREIGN KEY(id_variete) REFERENCES variete(id_variete)
) ENGINE=InnoDB;

-- =========================
-- TABLE TEMPERATURE
-- =========================
CREATE TABLE temperature(
   id_temperature INT AUTO_INCREMENT,
   temperature_valeur DECIMAL(4,1) NOT NULL,
   temperature_dateHeure DATETIME NOT NULL,
   adresse_capteur VARCHAR(32) NOT NULL,
   PRIMARY KEY(id_temperature),
   FOREIGN KEY(adresse_capteur) REFERENCES capteur(adresse_capteur)
) ENGINE=InnoDB;

-- =========================
-- TABLE LOT ETAGE
-- =========================
CREATE TABLE lotEtage(
   id_lot INT,
   id_etage INT,
   lotEtage_dateDebut DATETIME NOT NULL,
   lotEtage_dateFin DATETIME,
   PRIMARY KEY(id_lot, id_etage),
   FOREIGN KEY(id_lot) REFERENCES lot(id_lot),
   FOREIGN KEY(id_etage) REFERENCES etage(id_etage)
) ENGINE=InnoDB;

-- =========================
-- INDEX
-- =========================
CREATE INDEX idx_temp_capteur ON temperature(adresse_capteur);
CREATE INDEX idx_temp_date ON temperature(temperature_dateHeure);

-- =========================
-- USER APPLICATION
-- =========================
CREATE USER IF NOT EXISTS 'user_sechoir'@'localhost'
IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES
ON base_sechoir.*
TO 'user_sechoir'@'localhost';

FLUSH PRIVILEGES;