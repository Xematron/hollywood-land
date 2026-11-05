SET NAMES utf8mb4;

-- 1) Neteja de dades de prova residuals (si existeixen) -------------------
DELETE e FROM Entrada e
JOIN Sessio s ON s.id = e.sessio_id
JOIN Pelicula p ON p.id = s.pelicula_id
WHERE p.titol IN ('Pulp Ficion', 'QA Claude Peli editada');

DELETE d FROM Debat d
JOIN Sessio s ON s.id = d.sessio_id
JOIN Pelicula p ON p.id = s.pelicula_id
WHERE p.titol IN ('Pulp Ficion', 'QA Claude Peli editada');

DELETE s FROM Sessio s
JOIN Pelicula p ON p.id = s.pelicula_id
WHERE p.titol IN ('Pulp Ficion', 'QA Claude Peli editada');

-- Sessions soltes fora de patró (dates/hores de prova)
DELETE e FROM Entrada e
JOIN Sessio s ON s.id = e.sessio_id
WHERE (s.data = '2026-10-01')
   OR (s.data = '2026-10-28')
   OR (s.data = '2026-11-28' AND s.hora = '16:15:00')
   OR (s.data = '2026-11-29' AND s.hora = '21:00:00');

DELETE d FROM Debat d
JOIN Sessio s ON s.id = d.sessio_id
WHERE (s.data = '2026-10-01')
   OR (s.data = '2026-10-28')
   OR (s.data = '2026-11-28' AND s.hora = '16:15:00')
   OR (s.data = '2026-11-29' AND s.hora = '21:00:00');

DELETE FROM Sessio
WHERE (data = '2026-10-01')
   OR (data = '2026-10-28')
   OR (data = '2026-11-28' AND hora = '16:15:00')
   OR (data = '2026-11-29' AND hora = '21:00:00');

DELETE FROM Pelicula WHERE titol IN ('Pulp Ficion', 'QA Claude Peli editada');

-- 2) Ampliació del calendari: setmanes 3 a 7 (12/11 a 13/12) --------------
INSERT INTO Sessio (pelicula_id, sala_id, data, hora, preu, idioma_subtitols)
SELECT p.id, s.id, v.data, v.hora, v.preu, v.idioma_subtitols
FROM (
    SELECT 'Vertigo' AS titol, 'Sala 2 - Bogart' AS sala, '2026-11-12' AS data, '20:00:00' AS hora, 7.00 AS preu, 'Català' AS idioma_subtitols
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-13', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-13', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-14', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-14', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-15', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-15', '19:00:00', 7.00, 'Castellà'

    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-19', '20:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-20', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-20', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-21', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-21', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-22', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-22', '19:00:00', 7.00, 'Català'

    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-26', '20:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-27', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-27', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-28', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-28', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-29', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-29', '19:00:00', 7.00, 'Castellà'

    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-03', '20:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-04', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-04', '19:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-05', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-05', '19:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-06', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-06', '19:00:00', 8.00, 'Català'

    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-10', '20:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-11', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-11', '19:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-12', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-12', '19:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-13', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-13', '19:00:00', 8.00, 'Castellà'
) v
JOIN Pelicula p ON p.titol = v.titol
JOIN Sala s ON s.nom = v.sala
WHERE NOT EXISTS (
    SELECT 1 FROM Sessio s2
    WHERE s2.pelicula_id = p.id AND s2.sala_id = s.id AND s2.data = v.data AND s2.hora = v.hora
);

-- 3) Cine-fòrums per a les noves sessions (última sessió de cada dia nou) -
INSERT INTO Debat (sessio_id, descripcio, aforament, durada_min)
SELECT s.id, 'Cine-fòrum obert al públic després de la projecció.', 30, 60
FROM Sessio s
WHERE s.data IN ('2026-11-12','2026-11-13','2026-11-14','2026-11-15',
                 '2026-11-19','2026-11-20','2026-11-21','2026-11-22',
                 '2026-11-26','2026-11-27','2026-11-28','2026-11-29',
                 '2026-12-03','2026-12-04','2026-12-05','2026-12-06',
                 '2026-12-10','2026-12-11','2026-12-12','2026-12-13')
AND s.hora = (SELECT MAX(s2.hora) FROM Sessio s2 WHERE s2.data = s.data)
AND NOT EXISTS (SELECT 1 FROM Debat d WHERE d.sessio_id = s.id);
