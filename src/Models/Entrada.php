<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDO;

class Entrada
{
    public static function ocupades(int $sessioId): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT butaca_id FROM Entrada WHERE sessio_id = :sessio_id AND estat = 'pagada'"
        );
        $stmt->execute(['sessio_id' => $sessioId]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public static function create(int $sessioId, int $usuariId, int $butacaId, float $preuPagat): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Entrada (sessio_id, usuari_id, butaca_id, preu_pagat)
             VALUES (:sessio_id, :usuari_id, :butaca_id, :preu_pagat)'
        );
        $stmt->execute([
            'sessio_id' => $sessioId,
            'usuari_id' => $usuariId,
            'butaca_id' => $butacaId,
            'preu_pagat' => $preuPagat,
        ]);
    }

    public static function taquilleraPerPeriode(string $dataInici, string $dataFi): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT p.id, p.titol,
                    COUNT(e.id) AS entrades_venudes,
                    SUM(e.preu_pagat) AS ingressos
             FROM Entrada e
             JOIN Sessio s ON s.id = e.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             WHERE e.estat = 'pagada' AND s.data BETWEEN :inici AND :fi
             GROUP BY p.id, p.titol
             ORDER BY ingressos DESC"
        );
        $stmt->execute(['inici' => $dataInici, 'fi' => $dataFi]);

        return $stmt->fetchAll();
    }

    public static function ocupacioPerSessio(string $dataInici, string $dataFi): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT s.id, s.data, s.hora, p.titol, sa.nom AS sala_nom,
                    (SELECT COUNT(*) FROM Entrada e2
                       WHERE e2.sessio_id = s.id AND e2.estat = 'pagada') AS venudes,
                    (SELECT COUNT(*) FROM Butaca b
                       WHERE b.sala_id = s.sala_id AND b.operativa = 1) AS capacitat
             FROM Sessio s
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             WHERE s.data BETWEEN :inici AND :fi
             ORDER BY s.data, s.hora"
        );
        $stmt->execute(['inici' => $dataInici, 'fi' => $dataFi]);

        return $stmt->fetchAll();
    }

    public static function findByUsuari(int $usuariId): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT e.id, e.preu_pagat, e.data_compra, e.estat,
                    s.data, s.hora, p.titol, sa.nom AS sala_nom,
                    b.fila, b.numero
             FROM Entrada e
             JOIN Sessio s ON s.id = e.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             JOIN Butaca b ON b.id = e.butaca_id
             WHERE e.usuari_id = :usuari_id
             ORDER BY e.data_compra DESC"
        );
        $stmt->execute(['usuari_id' => $usuariId]);

        return $stmt->fetchAll();
    }
}
