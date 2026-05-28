USE base_sechoir;

-- =========================
-- RESET EVENEMENTS
-- =========================
DELETE FROM evenement;

-- réinitialiser AUTO_INCREMENT
ALTER TABLE evenement AUTO_INCREMENT = 1;