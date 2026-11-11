-- Migració BD remota (InfinityFree): rol 'taquiller' -> 'treballador'.
--
-- Context: el rol es va renombrar a local l'1/11/2026, però l'ENUM de
-- Usuari.rol a producció va quedar amb el valor antic ('taquiller').
-- En desar 'treballador' en un ENUM que no el conté, MySQL en mode no
-- estricte desa una cadena buida en lloc de donar error: l'usuari queda
-- sense rol i no pot entrar ni a la zona admin ni a la de treballador.
--
-- Executar al phpMyAdmin del vPanel, en aquest ordre. Reexecutable.

-- 1. Afegir 'treballador' sense treure encara 'taquiller'
ALTER TABLE Usuari MODIFY rol ENUM('admin','taquiller','treballador','client') NOT NULL DEFAULT 'client';

-- 2. Passar els antics taquillers a treballador
UPDATE Usuari SET rol = 'treballador' WHERE rol = 'taquiller';

-- 3. Arreglar els usuaris que havien quedat amb rol buit
--    (revisar abans amb SELECT: tots els afectats havien de ser treballadors)
UPDATE Usuari SET rol = 'treballador' WHERE rol = '';

-- 4. Treure 'taquiller' definitivament
ALTER TABLE Usuari MODIFY rol ENUM('admin','treballador','client') NOT NULL DEFAULT 'client';

-- Comprovació
SHOW COLUMNS FROM Usuari LIKE 'rol';
SELECT id, nom, email, rol FROM Usuari;
