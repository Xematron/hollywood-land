SET NAMES utf8mb4;

INSERT INTO Sala (nom, capacitat) VALUES
('Sala 1 - Lumière', 80),
('Sala 2 - Bogart', 60);

INSERT INTO Pelicula (titol, durada_min, idioma_original, sinopsi, classificacio) VALUES
('Casablanca', 102, 'Anglès', 'Un propietari de nit-club a Marroc es reveu amb el seu amor del passat.', 'APTA'),
('Blade Runner', 117, 'Anglès', 'Un caçador de recompenses persegueix replicants a Los Angeles 2019.', '16'),
('2001: Una Odissea de l\'Espai', 149, 'Anglès', 'Un viatge filosòfic sobre l\'evolució humana i la intel·ligència artificial.', 'APTA');

INSERT INTO Sessio (pelicula_id, sala_id, data, hora, preu, idioma_subtitols) VALUES
(1, 1, CURDATE(), '18:00:00', 6.50, 'Català'),
(2, 2, CURDATE(), '20:30:00', 7.00, 'Castellà'),
(3, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:00:00', 7.00, 'Català');
