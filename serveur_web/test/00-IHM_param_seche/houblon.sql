-- ==========================================
-- Base de données : sechoir_houblon
-- ==========================================

CREATE DATABASE IF NOT EXISTS sechoir_houblon;
USE sechoir_houblon;

-- ==========================================
-- Table : session_sechage
-- ==========================================

CREATE TABLE ses_sech (
    id_ses_sech INT AUTO_INCREMENT PRIMARY KEY,
    ses_sech_date_debut DATETIME NOT NULL,
    ses_sech_date_fin DATETIME NULL
);

-- ==========================================
-- Table : capteur
-- ==========================================

CREATE TABLE capteur (
    id_capt INT AUTO_INCREMENT PRIMARY KEY
);

-- ==========================================
-- Table : temperatures
-- ==========================================

CREATE TABLE temperatures (
    id_temp INT AUTO_INCREMENT PRIMARY KEY,
    temp_ses_sech INT NOT NULL,
    temp_capteur INT NOT NULL,
    temp_valeur FLOAT NOT NULL,
    temp_date_mesure DATETIME NOT NULL,

    FOREIGN KEY (temp_ses_sech)
        REFERENCES ses_sech(id_ses_sech)
        ON DELETE CASCADE,

    FOREIGN KEY (temp_capteur)
        REFERENCES capteur(id_capt)
        ON DELETE CASCADE
);

-- ==========================================
-- Table : evenements
-- ==========================================

CREATE TABLE evenements (
    id_event INT AUTO_INCREMENT PRIMARY KEY,
    event_ses_sech INT NOT NULL,
    event_src INT NOT NULL,
    event_type VARCHAR(255) NOT NULL,
    event_date DATETIME NOT NULL,

    FOREIGN KEY (event_ses_sech)
        REFERENCES ses_sech(id_ses_sech)
        ON DELETE CASCADE
);

-- ==========================================
-- Table : houblon variété
-- ==========================================

CREATE TABLE houb_var (
    id_houb_var INT AUTO_INCREMENT PRIMARY KEY,
    houb_var_type VARCHAR(100) NOT NULL
);

-- ==========================================
-- Table : masses houblon finale
-- ==========================================

CREATE TABLE m_houb_final (
    id_m_houbl_final INT AUTO_INCREMENT PRIMARY KEY,
    m_houbl_masse FLOAT NOT NULL,
    m_houbl_date_saisie DATETIME NOT NULL
);

-- ==========================================
-- Table : houblon sechage
-- ==========================================

CREATE TABLE houb_sech (
    id_houb_sech INT AUTO_INCREMENT PRIMARY KEY,
    houb_sech_m_houbl_final INT NULL,
    houb_sech_ses_sech INT NOT NULL,
    houb_sech_variete INT NOT NULL,
    houb_sech_etage INT NULL,
    houb_sech_date_in DATETIME NOT NULL,
    houb_sech_date_out DATETIME NULL,

    FOREIGN KEY (houb_sech_m_houbl_final)
        REFERENCES m_houb_final(id_m_houbl_final)
        ON DELETE SET NULL,

    FOREIGN KEY (houb_sech_ses_sech)
        REFERENCES ses_sech(id_ses_sech)
        ON DELETE CASCADE,

    FOREIGN KEY (houb_sech_variete)
        REFERENCES houb_var(id_houb_var)
        ON DELETE CASCADE
);

-- ==========================================
-- Données initiales capteurs
-- ==========================================

INSERT INTO capteur VALUES
(),
(),
();

INSERT INTO houb_var (houb_var_type) VALUES
("TypeA"),
("TypeB"),
("TypeC");