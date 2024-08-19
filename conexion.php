<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

$conexion = new mysqli($hostname, $username, $password, $database, $port);

if ($conexion->connect_errno) {
    echo "El sitio web está experimentado problemas";
    exit;
}

$conexion->set_charset("utf8mb4");

