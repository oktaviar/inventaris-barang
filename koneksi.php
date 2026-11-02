<?php

$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'inventaris_barang';
$port = getenv('DB_PORT') ?: 3306;

$conn = mysqli_init();

if (getenv('DB_HOST')) {
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
}

$conn->real_connect(
    $host,
    $username,
    $password,
    $database,
    (int)$port,
    NULL,
    MYSQLI_CLIENT_SSL
);

$conn->set_charset("utf8mb4");