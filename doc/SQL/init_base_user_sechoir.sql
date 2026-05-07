DROP DATABASE IF EXISTS base_sechoir;
CREATE DATABASE base_sechoir
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE base_sechoir;

-- ─────────────────────────────────────────────────────
-- TABLE : pause
-- ─────────────────────────────────────────────────────

CREATE TABLE pause(
   id_pause INT AUTO_INCREMENT,
   pause_type VARCHAR(64) NOT NULL,
   pause_dateHeureDebut DATETIME NOT NULL,
   pause_dateHeureFin DATETIME,
   PRIMARY KEY(id_pause)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : variete
-- ─────────────────────────────────────────────────────

CREATE TABLE variete(
   id_variete INT AUTO_INCREMENT,
   variete_nom VARCHAR(32) NOT NULL,
   variete_dateHeureCreation DATETIME NOT NULL,
   variete_actif BOOLEAN NOT NULL DEFAULT TRUE,
   PRIMARY KEY(id_variete)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : masse
-- ─────────────────────────────────────────────────────

CREATE TABLE masse(
   id_masse INT AUTO_INCREMENT,
   masse_masse DECIMAL(4,1) NOT NULL,
   masse_dateHeure DATETIME NOT NULL,
   PRIMARY KEY(id_masse)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : etage
-- ─────────────────────────────────────────────────────

CREATE TABLE etage(
   id_etage INT AUTO_INCREMENT,
   PRIMARY KEY(id_etage)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : etatSechoir
-- ─────────────────────────────────────────────────────

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

-- ─────────────────────────────────────────────────────
-- TABLE : capteur
-- ─────────────────────────────────────────────────────

CREATE TABLE capteur(
   addresse_capteur VARCHAR(32),
   capteur_nom VARCHAR(32) NOT NULL,
   capteur_gpio VARCHAR(8) NOT NULL,
   capteur_actif BOOLEAN NOT NULL DEFAULT TRUE,
   PRIMARY KEY(addresse_capteur)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : evenement
-- ─────────────────────────────────────────────────────

CREATE TABLE evenement(
   id_event INT AUTO_INCREMENT,
   event_type VARCHAR(64) NOT NULL,
   event_description VARCHAR(2048) NOT NULL,
   event_dateHeureDebut DATETIME NOT NULL,
   event_dateHeureFin DATETIME,
   PRIMARY KEY(id_event)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : lot
-- ─────────────────────────────────────────────────────

CREATE TABLE lot(
   id_lot INT AUTO_INCREMENT,
   lot_remplissage TINYINT NOT NULL,
   lot_dateHeureEntree DATETIME NOT NULL,
   lot_dateHeureSortie DATETIME,
   lot_dureeTheorique INT NOT NULL,
   lot_actif BOOLEAN NOT NULL DEFAULT TRUE,
   id_masse INT,
   id_variete INT NOT NULL,

   PRIMARY KEY(id_lot),

   CONSTRAINT fk_lot_masse
      FOREIGN KEY(id_masse)
      REFERENCES masse(id_masse)
      ON DELETE SET NULL
      ON UPDATE CASCADE,

   CONSTRAINT fk_lot_variete
      FOREIGN KEY(id_variete)
      REFERENCES variete(id_variete)
      ON DELETE RESTRICT
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : temperature
-- ─────────────────────────────────────────────────────

CREATE TABLE temperature(
   id_temperature INT AUTO_INCREMENT,
   temperature_valeur DECIMAL(4,1) NOT NULL,
   temperature_dateHeure DATETIME NOT NULL,
   addresse_capteur VARCHAR(32) NOT NULL,

   PRIMARY KEY(id_temperature),

   CONSTRAINT fk_temperature_capteur
      FOREIGN KEY(addresse_capteur)
      REFERENCES capteur(addresse_capteur)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- TABLE : lotEtage
-- ─────────────────────────────────────────────────────

CREATE TABLE lotEtage(
   id_lot INT,
   id_etage INT,
   lotEtage_dateDebut DATETIME NOT NULL,
   lotEtage_dateFin DATETIME,

   PRIMARY KEY(id_lot, id_etage),

   CONSTRAINT fk_lotEtage_lot
      FOREIGN KEY(id_lot)
      REFERENCES lot(id_lot)
      ON DELETE CASCADE
      ON UPDATE CASCADE,

   CONSTRAINT fk_lotEtage_etage
      FOREIGN KEY(id_etage)
      REFERENCES etage(id_etage)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────
-- INDEX
-- ─────────────────────────────────────────────────────

CREATE INDEX idx_temperature_date
ON temperature(temperature_dateHeure);

CREATE INDEX idx_evenement_date
ON evenement(event_dateHeureDebut);

CREATE INDEX idx_lot_actif
ON lot(lot_actif);

CREATE INDEX idx_capteur_actif
ON capteur(capteur_actif);

-- ─────────────────────────────────────────────────────
-- UTILISATEUR APPLICATION
-- ─────────────────────────────────────────────────────

CREATE USER IF NOT EXISTS 'user_sechoir'@'localhost'
IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES
ON base_sechoir.*
TO 'user_sechoir'@'localhost';

FLUSH PRIVILEGES;