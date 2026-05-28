USE base_sechoir;

-- =========================
-- RESET ETAT SECHOIR
-- =========================
DELETE FROM etatSechoir;

-- réinitialiser AUTO_INCREMENT
ALTER TABLE etatSechoir AUTO_INCREMENT = 1;