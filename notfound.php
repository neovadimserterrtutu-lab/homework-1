<?php

declare(strict_types=1);

http_response_code(404);
// Дополнительно можно явно указать статус:
header('Status: 404 Not Found');

exit;
