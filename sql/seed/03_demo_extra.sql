SET NAMES utf8mb4;

-- Cine-fòrum (1h) després de l'última sessió de cada dia, excepte l'1/11
-- (aquell dia no hi ha cine-fòrum: en lloc seu hi ha el taller "Nit de terror")
INSERT INTO Debat (sessio_id, descripcio, aforament, durada_min)
SELECT s.id, 'Cine-fòrum obert al públic després de la projecció.', NULL, 60
FROM Sessio s
WHERE s.data <> '2026-11-01'
AND s.hora = (SELECT MAX(s2.hora) FROM Sessio s2 WHERE s2.data = s.data);

-- Taller/podcast "Nit de terror" el dia del marató de Halloween (1/11)
INSERT INTO Activitat (tipus, titol, descripcio, data, hora, durada_min, sala_id)
VALUES ('taller', 'Nit de terror', 'Gravació en directe d''un podcast sobre cinema de terror, oberta al públic.', '2026-11-01', '21:00:00', 120, NULL);
