<?php

session_start();

require_once "config/database.php";


$id = (int) ($_POST['id'] ?? 0);

$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');


/*
|--------------------------------------------------------------------------
| VALIDASI ID
|--------------------------------------------------------------------------
*/

if ($id <= 0) {

    $_SESSION['message'] =
        "ID tidak valid.";

    $_SESSION['message_type'] =
        "error";

    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDASI INPUT
|--------------------------------------------------------------------------
*/

if ($nama === '' || $nim === '' || $alamat === '') {

    $_SESSION['message'] =
        "Semua field wajib diisi.";

    $_SESSION['message_type'] =
        "error";

    header("Location: edit.php?id=$id");
    exit;
}


if (strlen($nama) < 3) {

    $_SESSION['message'] =
        "Nama minimal 3 karakter.";

    $_SESSION['message_type'] =
        "error";

    header("Location: edit.php?id=$id");
    exit;
}


if (!preg_match('/^[0-9]+$/', $nim)) {

    $_SESSION['message'] =
        "NIM hanya boleh berisi angka.";

    $_SESSION['message_type'] =
        "error";

    header("Location: edit.php?id=$id");
    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

try {

    $query = $pdo->prepare(
        "UPDATE mahasiswa
         SET nama = :nama,
             nim = :nim,
             alamat = :alamat
         WHERE id = :id"
    );


    $query->execute([
        ":nama" => $nama,
        ":nim" => $nim,
        ":alamat" => $alamat,
        ":id" => $id
    ]);


    $_SESSION['message'] =
        "Data mahasiswa berhasil diperbarui.";

    $_SESSION['message_type'] =
        "success";


    header("Location: index.php");
    exit;


} catch (PDOException $e) {

    if ($e->getCode() == 23000) {

        $_SESSION['message'] =
            "NIM tersebut sudah digunakan.";

    } else {

        $_SESSION['message'] =
            "Gagal memperbarui data.";
    }


    $_SESSION['message_type'] =
        "error";


    header("Location: edit.php?id=$id");
    exit;
}