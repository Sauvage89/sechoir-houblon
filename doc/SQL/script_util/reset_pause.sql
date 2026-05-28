USE base_sechoir;

-- =========================
-- RESET PAUSE
-- =========================
DELETE FROM pause;

-- réinitialiser AUTO_INCREMENT
ALTER TABLE pause AUTO_INCREMENT = 1;