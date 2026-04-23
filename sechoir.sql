CREATE TABLE pause(
   id_pause INT AUTO_INCREMENT,
   pause_type VARCHAR(64)  NOT NULL,
   pause_dateHeureDebut DATETIME NOT NULL,
   pause_dateHeureFin DATETIME,
   PRIMARY KEY(id_pause)
);
CREATE TABLE variete(
   id_variete INT AUTO_INCREMENT,
   variete_nom VARCHAR(32)  NOT NULL,
   variete_dateHeureCreation DATETIME NOT NULL,
   variete_actif BOOLEAN NOT NULL,
   PRIMARY KEY(id_variete)
);

CREATE TABLE masse(
   id_masse INT AUTO_INCREMENT,
   masse_masse DECIMAL(4,2)   NOT NULL,
   masse_dateHeure DATETIME NOT NULL,
   PRIMARY KEY(id_masse)
);

CREATE TABLE etage(
   id_etage INT AUTO_INCREMENT,
   PRIMARY KEY(id_etage)
);

CREATE TABLE etatSechoir(
   id_etatSechoir INT AUTO_INCREMENT,
   etatSechoir_status VARCHAR(32)  NOT NULL,
   etatSechoir_dataMaj DATETIME,
   etatSechoir_pauseDebut DATETIME,
   etatSechoir_ajoutMinute SMALLINT NOT NULL,
   PRIMARY KEY(id_etatSechoir)
);

CREATE TABLE capteur(
   addresse_capteur VARCHAR(32) ,
   capteur_nom VARCHAR(32) ,
   capteur_gpio VARCHAR(8) ,
   capteur_actif BOOLEAN NOT NULL,
   PRIMARY KEY(addresse_capteur)
);

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
);

CREATE TABLE temperature(
   id_temperature INT AUTO_INCREMENT,
   temperature_valeur DECIMAL(3,1)   NOT NULL,
   temperature_dateHeure DATETIME NOT NULL,
   addresse_capteur VARCHAR(32)  NOT NULL,
   PRIMARY KEY(id_temperature),
   FOREIGN KEY(addresse_capteur) REFERENCES capteur(addresse_capteur)
);

CREATE TABLE lotEtage(
   id_lot INT,
   id_etage INT,
   lotEtage_dateDebut DATETIME NOT NULL,
   lotEtage_dateFin DATETIME,
   PRIMARY KEY(id_lot, id_etage),
   FOREIGN KEY(id_lot) REFERENCES lot(id_lot),
   FOREIGN KEY(id_etage) REFERENCES etage(id_etage)
);
