<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Sessio
{
    public static function cartellera(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT s.id, s.data, s.hora, s.preu, s.idioma_subtitols,
                    p.titol, p.durada_min, p.classificacio,
                    sa.nom AS sala_nom
             FROM Sessio s
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             WHERE s.data >= CURDATE()
             ORDER BY s.data, s.hora'
        );

        return $stmt->fetchAll();
    }
}