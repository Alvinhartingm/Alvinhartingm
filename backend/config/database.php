<?php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_NAME') ?: 'servitem_crm_saas',
    'username' => getenv('DB_USER') ?: 'servitem_admin_saas',
    'password' => getenv('DB_PASS') ?: 's3rv1t.01',
    'charset' => 'utf8mb4',
];
