SET NAMES utf8mb4;

INSERT INTO Sala (nom, capacitat) VALUES
('Sala 1 - Lumière', 80),
('Sala 2 - Bogart', 60);

INSERT INTO Pelicula (titol, durada_min, idioma_original, sinopsi, classificacio) VALUES
('Casablanca', 102, 'Anglès', 'Un propietari de nit-club a Marroc es reveu amb el seu amor del passat.', 'APTA'),
('Blade Runner', 117, 'Anglès', 'Un caçador de recompenses persegueix replicants a Los Angeles 2019.', '16'),
('2001: Una Odissea de l\'Espai', 149, 'Anglès', 'Un viatge filosòfic sobre l\'evolució humana i la intel·ligència artificial.', 'APTA'),
('Metropolis', 153, 'Alemany', 'Visió distòpica d\'una ciutat futurista dividida entre treballadors i elit dirigent.', 'APTA'),
('Vertigo', 128, 'Anglès', 'Un detectiu amb vertigen investiga l\'obsessió d\'un amic per la seva dona.', '12'),
('Halloween', 91, 'Anglès', 'Michael Myers torna al seu poble natal la nit de Halloween.', '16'),
('Halloween II', 92, 'Anglès', 'La història continua directament després dels fets de la primera nit.', '16'),
('Halloween III: Season of the Witch', 98, 'Anglès', 'Un fabricant de màscares prepara un pla terrible per a la nit de Halloween.', '16');
