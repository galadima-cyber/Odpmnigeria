<?php
// Site configuration - SAMPLE FILE
// Copy this to config.php and update with your actual credentials

return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'odpm_db',
        'user' => 'root',
        'pass' => 'YOUR_DATABASE_PASSWORD_HERE',
        'charset' => 'utf8mb4',
    ],
    'site' => [
        'name' => 'ODPM Nigeria',
        'base_url' => '/ODPM',
        'upload_dir' => __DIR__ . '/uploads',
        'upload_base_url' => '/ODPM/uploads',
        'max_upload_bytes' => 5 * 1024 * 1024, // 5MB
        'allowed_image_types' => ['image/jpeg','image/png','image/gif','image/webp'],
        'allowed_file_types' => [
            'image/jpeg','image/png','image/gif','image/webp','application/pdf'
        ],
    ],
    'email' => [
        'admin_email' => 'admin@yourdomain.com',
        'from_email' => 'noreply@yourdomain.com',
        'from_name' => 'ODPM Nigeria',
        'smtp_host' => 'localhost',
        'smtp_port' => 25,
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_secure' => '', // 'tls' or 'ssl' or leave empty
    ],
];
