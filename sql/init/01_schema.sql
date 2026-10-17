SET NAMES utf8mb4;

CREATE TABLE Usuari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'taquiller', 'client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Pelicula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titol VARCHAR(200) NOT NULL,
    durada_min INT NOT NULL,
    idioma_original VARCHAR(50) NOT NULL,
    sinopsi TEXT,
    cartell_img VARCHAR(255),
    classificacio VARCHAR(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE CicleTematic (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    descripcio TEXT,
    data_inici DATE,
    data_fi DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Sala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    capacitat INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Butaca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sala_id INT NOT NULL,
    fila VARCHAR(5) NOT NULL,
    numero INT NOT NULL,
    UNIQUE (sala_id, fila, numero),
    FOREIGN KEY (sala_id) REFERENCES Sala(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Sessio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    sala_id INT NOT NULL,
    cicle_id INT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    preu DECIMAL(5,2) NOT NULL,
    idioma_subtitols VARCHAR(50),
    FOREIGN KEY (pelicula_id) REFERENCES Pelicula(id),
    FOREIGN KEY (sala_id) REFERENCES Sala(id),
    FOREIGN KEY (cicle_id) REFERENCES CicleTematic(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Entrada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessio_id INT NOT NULL,
    usuari_id INT NOT NULL,
    butaca_id INT NOT NULL,
    preu_pagat DECIMAL(5,2) NOT NULL,
    data_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    estat ENUM('pagada', 'cancelada') NOT NULL DEFAULT 'pagada',
    UNIQUE (sessio_id, butaca_id),
    FOREIGN KEY (sessio_id) REFERENCES Sessio(id),
    FOREIGN KEY (usuari_id) REFERENCES Usuari(id),
    FOREIGN KEY (butaca_id) REFERENCES Butaca(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Debat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessio_id INT NOT NULL,
    descripcio TEXT,
    aforament INT,
    FOREIGN KEY (sessio_id) REFERENCES Sessio(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE InscripcioDebat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debat_id INT NOT NULL,
    usuari_id INT NOT NULL,
    UNIQUE (debat_id, usuari_id),
    FOREIGN KEY (debat_id) REFERENCES Debat(id),
    FOREIGN KEY (usuari_id) REFERENCES Usuari(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
