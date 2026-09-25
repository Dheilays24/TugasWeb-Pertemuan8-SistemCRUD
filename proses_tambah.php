<?php

session_start();

require_once "config/database.php";


/*
|--------------------------------------------------------------------------
| Mengambil data
|--------------------------------------------------------------------------
*/

$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');


/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($nama === '' || $nim === '' || $alamat === '') {

    $_SESSION['message'] =
        "Semua field wajib diisi.";

    $_SESSION['message_type'] =
        "error";

    header("Location: tambah.php");
    exit;
}


if (strlen($nama) < 3) {

    $_SESSION['message'] =
        "Nama minimal 3 karakter.";

    $_SESSION['message_type'] =
        "error";

    header("Location: tambah.php");
    exit;
}


if (!preg_match('/^[0-9]+$/', $nim)) {

    $_SESSION['message'] =
        "NIM hanya boleh berisi angka.";

    $_SESSION['message_type'] =
        "error";

    header("Location: tambah.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| INSERT DENGAN PREPARED STATEMENT
|--------------------------------------------------------------------------
*/

try {

    $query = $pdo->prepare(
        "INSERT INTO mahasiswa
        (nama, nim, alamat)
        VALUES
        (:nama, :nim, :alamat)"
    );

    $query->execute([
        ":nama" => $nama,
        ":nim" => $nim,
        ":alamat" => $alamat
    ]);


    /*
    |--------------------------------------------------------------------------
    | BERHASIL
    |--------------------------------------------------------------------------
    */

    $_SESSION['message'] =
        "Data mahasiswa berhasil ditambahkan.";

    $_SESSION['message_type'] =
        "success";


    header("Location: index.php");
    exit;


} catch (PDOException $e) {


    /*
    |--------------------------------------------------------------------------
    | NIM DUPLIKAT
    |--------------------------------------------------------------------------
    */

    if ($e->getCode() == 23000) {

        $_SESSION['message'] =
            "NIM tersebut sudah terdaftar.";

    } else {

        $_SESSION['message'] =
            "Terjadi kesalahan saat menyimpan data.";
    }


    $_SESSION['message_type'] =
        "error";


    header("Location: tambah.php");
    exit;
}