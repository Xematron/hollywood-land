SET NAMES utf8mb4;

-- Calendari de sessions de demostració: dijous a diumenge, fins al 13/12
-- (últim diumenge abans del 15/12; el negoci no obre dilluns/dimarts).
-- Dijous: sessió única a les 20h. Divendres i dissabte: doble sessió (16h/19h).
-- Diumenge 1/11: marató temàtic de Halloween (3 sessions) en lloc del programa habitual.
-- Es recuperen els ids per nom (pel·lícula/sala) per no dependre de l'ordre d'inserció de 01_demo.sql
INSERT INTO Sessio (pelicula_id, sala_id, data, hora, preu, idioma_subtitols)
SELECT p.id, s.id, v.data, v.hora, v.preu, v.idioma_subtitols
FROM (
    -- Setmana 1: dijous 29/10 a diumenge 1/11
    SELECT 'Blade Runner' AS titol, 'Sala 2 - Bogart' AS sala, '2026-10-29' AS data, '20:00:00' AS hora, 7.00 AS preu, 'Castellà' AS idioma_subtitols
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-10-30', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-10-30', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-10-31', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-10-31', '19:00:00', 7.00, 'Castellà'

    -- Diumenge 1/11: Marató de Halloween (substitueix el programa habitual)
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-01', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 1 - Marilyn', '2026-11-01', '18:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 1 - Marilyn', '2026-11-01', '20:00:00', 8.00, 'Català'

    -- Setmana 2: dijous 5/11 a diumenge 8/11
    UNION ALL SELECT 'Metropolis' AS titol, 'Sala 2 - Bogart' AS sala, '2026-11-05', '20:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-06', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-06', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-07', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-07', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-08', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-08', '19:00:00', 7.00, 'Català'

    -- Setmana 3: dijous 12/11 a diumenge 15/11
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-12', '20:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-13', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-13', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-14', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-14', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-11-15', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 2 - Bogart', '2026-11-15', '19:00:00', 7.00, 'Castellà'

    -- Setmana 4: dijous 19/11 a diumenge 22/11
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-19', '20:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-20', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-20', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-21', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-21', '19:00:00', 7.00, 'Català'
    UNION ALL SELECT '2001: Una Odissea de l\'Espai', 'Sala 1 - Marilyn', '2026-11-22', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Metropolis', 'Sala 2 - Bogart', '2026-11-22', '19:00:00', 7.00, 'Català'

    -- Setmana 5: dijous 26/11 a diumenge 29/11
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-26', '20:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-27', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-27', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-28', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-28', '19:00:00', 7.00, 'Castellà'
    UNION ALL SELECT 'Halloween', 'Sala 1 - Marilyn', '2026-11-29', '16:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Blade Runner', 'Sala 2 - Bogart', '2026-11-29', '19:00:00', 7.00, 'Castellà'

    -- Setmana 6: dijous 3/12 a diumenge 6/12
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-03', '20:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-04', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-04', '19:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-05', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-05', '19:00:00', 8.00, 'Català'
    UNION ALL SELECT 'Vertigo', 'Sala 1 - Marilyn', '2026-12-06', '16:00:00', 7.00, 'Català'
    UNION ALL SELECT 'Halloween II', 'Sala 2 - Bogart', '2026-12-06', '19:00:00', 8.00, 'Català'

    -- Setmana 7: dijous 10/12 a diumenge 13/12
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-10', '20:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-11', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-11', '19:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-12', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-12', '19:00:00', 8.00, 'Castellà'
    UNION ALL SELECT 'Casablanca', 'Sala 1 - Marilyn', '2026-12-13', '16:00:00', 6.50, 'Català'
    UNION ALL SELECT 'Halloween III: Season of the Witch', 'Sala 2 - Bogart', '2026-12-13', '19:00:00', 8.00, 'Castellà'
) v
JOIN Pelicula p ON p.titol = v.titol
JOIN Sala s ON s.nom = v.sala;
