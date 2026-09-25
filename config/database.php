<?php

/**
 * File koneksi database menggunakan PDO.
 */

// Konfigurasi database
$host = "localhost";
$dbname = "akademik";
$username = "root";
$password = "";

// DSN untuk koneksi MySQL
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {

    // Membuat koneksi PDO
    $pdo = new PDO($dsn, $username, $password);

    // Menampilkan error sebagai Exception
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Hasil query SELECT dikembalikan sebagai associative array
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    // Mematikan emulasi prepared statement
    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {

    // Jika koneksi gagal
    die("Koneksi database gagal.");
}