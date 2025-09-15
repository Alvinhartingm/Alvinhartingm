<?php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_NAME') ?: 'crm_saas',
    'username' => getenv('DB_USER') ?: 'crm_user',
    'password' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
