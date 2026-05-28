USE base_sechoir;

-- =========================
-- 1) VIDER LES TEMPERATURES
-- =========================
DELETE FROM temperature;

-- =========================
-- 2) RESET EVENEMENTS
-- =========================
DELETE FROM evenement;

-- =========================
-- 3) RESET PAUSE
-- =========================
DELETE FROM pause;

-- =========================
-- 4) RESET ETAT SECHOIR
-- =========================
DELETE FROM etatSechoir;


-- réinitialiser AUTO_INCREMENT
ALTER TABLE temperature AUTO_INCREMENT = 1;
ALTER TABLE evenement AUTO_INCREMENT = 1;
ALTER TABLE pause AUTO_INCREMENT = 1;
ALTER TABLE etatSechoir AUTO_INCREMENT = 1;