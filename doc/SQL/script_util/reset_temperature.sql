USE base_sechoir;

-- =========================
-- VIDER LES TEMPERATURES
-- =========================
DELETE FROM temperature;

-- réinitialiser AUTO_INCREMENT
ALTER TABLE temperature AUTO_INCREMENT = 1;