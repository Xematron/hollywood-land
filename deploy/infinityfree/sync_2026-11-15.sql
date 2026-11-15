-- Sincronització de la BD remota (InfinityFree) — 2026-11-15
-- Executar al phpMyAdmin del vPanel, pestanya SQL, sobre la BD del projecte.
-- Reexecutable: tornar-lo a passar no canvia res més.
--
-- 1) Corregeix textos amb accents trencats (UTF-8 llegit com a Latin-1 en la
--    càrrega inicial: "histÃ²ria", "mÃ scares", "GravaciÃ³").
-- 2) Esborra les dades de prova que va deixar el test del 15/11/2026.

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------------
-- 1) Accents
-- ---------------------------------------------------------------------------
UPDATE Pelicula
SET sinopsi = 'La història continua directament després dels fets de la primera nit.'
WHERE titol = 'Halloween II';

UPDATE Pelicula
SET sinopsi = 'Un fabricant de màscares prepara un pla terrible per a la nit de Halloween.'
WHERE titol = 'Halloween III: Season of the Witch';

UPDATE Activitat
SET descripcio = 'Gravació en directe d''un podcast sobre cinema de terror, oberta al públic.'
WHERE titol = 'Nit de terror';

-- Descripció genèrica dels cine-fòrums: unifica les variants trencades
-- ("Cine-fÃ²rum obert al pÃºblic...") i la que tenia l'accent mal posat ("Cine-fórum").
UPDATE Debat
SET descripcio = 'Cine-fòrum obert al públic després de la projecció.'
WHERE descripcio LIKE 'Cine-f%rum obert al p%blic despr%s de la projecci%';

-- ---------------------------------------------------------------------------
-- 2) Dades de prova del test
-- ---------------------------------------------------------------------------
-- Pel·lícula "TEST QA (es pot esborrar)" amb les seves sessions, entrades i debats
-- (ordre obligat per les claus foranes: fills abans que pares).
DELETE FROM Entrada
WHERE sessio_id IN (SELECT id FROM Sessio WHERE pelicula_id IN (SELECT id FROM Pelicula WHERE titol LIKE 'TEST QA%'));

DELETE FROM InscripcioDebat
WHERE debat_id IN (SELECT id FROM Debat WHERE sessio_id IN (
    SELECT id FROM Sessio WHERE pelicula_id IN (SELECT id FROM Pelicula WHERE titol LIKE 'TEST QA%')));

DELETE FROM Debat
WHERE sessio_id IN (SELECT id FROM Sessio WHERE pelicula_id IN (SELECT id FROM Pelicula WHERE titol LIKE 'TEST QA%'));

DELETE FROM Sessio
WHERE pelicula_id IN (SELECT id FROM Pelicula WHERE titol LIKE 'TEST QA%');

DELETE FROM Pelicula WHERE titol LIKE 'TEST QA%';

-- Entrades comprades per l'admin durant el test (sessions 70 i 75, el mateix 15/11)
DELETE FROM Entrada
WHERE sessio_id IN (70, 75)
  AND usuari_id = (SELECT id FROM Usuari WHERE email = 'admin@hl.com')
  AND DATE(data_compra) = '2026-11-15';

-- Inscripció de 2 persones de l'admin al cine-fòrum 13
DELETE FROM InscripcioDebat
WHERE debat_id = 13
  AND usuari_id = (SELECT id FROM Usuari WHERE email = 'admin@hl.com');

-- ---------------------------------------------------------------------------
-- Comprovació (tot hauria de sortir buit o a 0)
-- ---------------------------------------------------------------------------
-- COLLATE utf8mb4_bin: sense això, 'Ã' coincideix amb qualsevol 'a' (col·lació insensible a accents).
SELECT 'Pelicula' AS taula, id, titol AS text_trencat FROM Pelicula
WHERE titol COLLATE utf8mb4_bin LIKE '%Ã%' OR sinopsi COLLATE utf8mb4_bin LIKE '%Ã%'
   OR idioma_original COLLATE utf8mb4_bin LIKE '%Ã%'
UNION ALL
SELECT 'Activitat', id, titol FROM Activitat
WHERE titol COLLATE utf8mb4_bin LIKE '%Ã%' OR descripcio COLLATE utf8mb4_bin LIKE '%Ã%'
UNION ALL
SELECT 'Debat', id, COALESCE(titol, '(sense títol)') FROM Debat
WHERE titol COLLATE utf8mb4_bin LIKE '%Ã%' OR descripcio COLLATE utf8mb4_bin LIKE '%Ã%'
UNION ALL
SELECT 'Sessio', id, idioma_subtitols FROM Sessio
WHERE idioma_subtitols COLLATE utf8mb4_bin LIKE '%Ã%'
UNION ALL
SELECT 'Sala', id, nom FROM Sala WHERE nom COLLATE utf8mb4_bin LIKE '%Ã%'
UNION ALL
SELECT 'Usuari', id, nom FROM Usuari WHERE nom COLLATE utf8mb4_bin LIKE '%Ã%';

SELECT COUNT(*) AS pelicules_test_restants FROM Pelicula WHERE titol LIKE 'TEST QA%';
