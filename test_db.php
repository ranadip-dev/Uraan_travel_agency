<?php

$host = "127.0.0.1";
$dbname = "uraan_travel_agency";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "<h1 style='color:green'>✅ Database Connected Successfully</h1>";

} catch (PDOException $e) {
    echo "<pre>";
    echo $e->getMessage();
    echo "</pre>";
}