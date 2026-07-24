<?php

declare(strict_types=1);

$databaseHost = '127.0.0.1';
$databasePort = '3306';
$databaseName = 'uraan_travel_agency';
$databaseUser = 'root';
$databasePassword = '';

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $databaseHost,
    $databasePort,
    $databaseName
);

try {
    $pdo = new PDO(
        $dsn,
        $databaseUser,
        $databasePassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());

    http_response_code(500);
    exit('Unable to connect to the database. Please try again later.');
}