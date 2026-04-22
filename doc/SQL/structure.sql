-- ========================================================
-- Base de données pour le suivi des lots de houblon
-- Compatible MariaDB
-- ========================================================

CREATE DATABASE IF NOT EXISTS sechoir_houblon;
USE sechoir_houblon;

-- ========================================================
-- Table: composant
-- ========================================================
CREATE TABLE compo (
    id_compo INT AUTO_INCREMENT PRIMARY KEY,
    compo_type VARCHAR(32) NOT NULL,
    compo_actif BOOL NOT NULL DEFAULT 1
);

-- ========================================================
-- Table: evenement
-- ========================================================
CREATE TABLE even (
    id_even INT AUTO_INCREMENT PRIMARY KEY,
    even_compo INT NOT NULL,
    even_type VARCHAR(128) NOT NULL,
    even_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (even_compo) REFERENCES compo(id_compo)
);

-- ========================================================
-- Table: temperature
-- ========================================================
CREATE TABLE temp (
    id_temp INT AUTO_INCREMENT PRIMARY KEY,
    temp_compo INT NOT NULL,
    temp_valeur DECIMAL(3,1) NOT NULL,
    temp_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (temp_compo) REFERENCES compo(id_compo)
);

-- ========================================================
-- Table: houblon variete
-- ========================================================
CREATE TABLE houbVar (
    id_houbVar INT AUTO_INCREMENT PRIMARY KEY,
    houbVar_type VARCHAR(32) NOT NULL,
    houbVar_activ BOOL NOT NULL DEFAULT 1
);

-- ========================================================
-- Table: houblon final
-- ========================================================
CREATE TABLE houbFinal (
    id_houbFinal INT AUTO_INCREMENT PRIMARY KEY,
    houbFinal_masse DECIMAL(5,2) NOT NULL,
    houbFinal_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ========================================================
-- Table: houblon lot
-- ========================================================
CREATE TABLE houbLot (
    id_houbLot INT AUTO_INCREMENT PRIMARY KEY,
    houbLot_houbFinal INT DEFAULT NULL,
    houbLot_houbVar INT NOT NULL,
    houbLot_dateDebut DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    houbLot_dateFin DATETIME DEFAULT NULL,
    FOREIGN KEY (houbLot_houbFinal) REFERENCES houbFinal(id_houbFinal),
    FOREIGN KEY (houbLot_houbVar) REFERENCES houbVar(id_houbVar)
);

-- ========================================================
-- Table: houblon etage
-- ========================================================
CREATE TABLE houbEtag (
    id_houbEtag INT AUTO_INCREMENT PRIMARY KEY,
    houbEtag_houbLot INT NOT NULL,
    houbEtag_etage TINYINT NOT NULL,
    houbEtag_duree INT DEFAULT NULL,
    houbEtag_actif BOOL NOT NULL DEFAULT 1,
    FOREIGN KEY (houbEtag_houbLot) REFERENCES houbLot(id_houbLot)
);

-- ========================================================
-- Table: lien evenement houblon etage
-- ========================================================
CREATE TABLE lienEvenHoubEtag (
    lienEvenHoubEtag_houbEtag INT NOT NULL,
    lienEvenHoubEtag_even INT NOT NULL,
    PRIMARY KEY (lienEvenHoubEtag_houbEtag, lienEvenHoubEtag_even),
    FOREIGN KEY (lienEvenHoubEtag_houbEtag) REFERENCES houbEtag(id_houbEtag),
    FOREIGN KEY (lienEvenHoubEtag_even) REFERENCES even(id_even)
);

-- ========================================================
-- Table: lien temperature houblon etage
-- ========================================================
CREATE TABLE lienTempHoubEtag (
    lienTempHoubEtag_houbEtag INT NOT NULL,
    lienTempHoubEtag_temp INT NOT NULL,
    PRIMARY KEY (lienTempHoubEtag_houbEtag, lienTempHoubEtag_temp),
    FOREIGN KEY (lienTempHoubEtag_houbEtag) REFERENCES houbEtag(id_houbEtag),
    FOREIGN KEY (lienTempHoubEtag_temp) REFERENCES temp(id_temp)
);

-- ========================================================
-- Index et contraintes supplémentaires
-- ========================================================
-- Un lot ne peut être actif que sur un seul étage
ALTER TABLE houbEtag
ADD CONSTRAINT unique_active_etage_per_lot UNIQUE (houbEtag_houbLot, houbEtag_etage, houbEtag_actif);

-- ========================================================
-- Fin du script
-- ========================================================