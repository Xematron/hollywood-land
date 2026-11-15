<?php

// Pàgina 404 general: Apache hi envia qualsevol URL inexistent
// (ErrorDocument a deploy/infinityfree/htdocs.htaccess).
require __DIR__ . '/../src/bootstrap.php';

http_response_code(404);
$missatge404 = 'Aquesta pàgina no existeix.';
require __DIR__ . '/partials/error404.php';
