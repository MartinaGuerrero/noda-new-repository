-- Migration: Add primaria and secundaria flags to usuario
-- Usage: mysql -u root -p novosis_noda < migrations/001_add_primaria_secundaria.sql

-- 1) Backup recommendation (run before executing this file):
-- mysqldump -u root -p novosis_noda > ~/novosis_backup_YYYY-MM-DD.sql

-- 2) Add columns
ALTER TABLE usuario
  ADD COLUMN primaria TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN secundaria TINYINT(1) NOT NULL DEFAULT 0;

-- 3) Optional: verify (client): DESCRIBE usuario;

-- 4) Examples to populate initial values (edit emails as needed):
-- UPDATE usuario SET primaria = 1 WHERE email IN ('rlockhar@impulso.edu.uy');
-- UPDATE usuario SET secundaria = 1 WHERE email IN ('ntorres@impulso.edu.uy');

-- Rollback (if needed):
-- ALTER TABLE usuario DROP COLUMN primaria, DROP COLUMN secundaria;
