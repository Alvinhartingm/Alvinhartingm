<?php
use App\Controllers\LeadController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/leads') {
    (new LeadController($pdo))->index();
    return;
}

http_response_code(404);
echo 'Not Found';
