<?php

require_once "config/database.php";

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID mahasiswa tidak valid.");
}


$query = $pdo->prepare(
    "SELECT *
     FROM mahasiswa
     WHERE id = :id"
);

$query->execute([
    ":id" => $id
]);

$data = $query->fetch();


if (!$data) {
    die("Data mahasiswa tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Mahasiswa</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="page">

    <div class="form-page">

        <a href="index.php" class="back">
            ← Kembali
        </a>

        <div class="form-card">

            <span class="badge">
                UPDATE DATA
            </span>

            <h1>
                Edit Mahasiswa
            </h1>

            <p>
                Perbarui informasi mahasiswa.
            </p>


            <form
                method="POST"
                action="proses_edit.php"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $data['id'] ?>"
                >


                <div class="form-group">

                    <label for="nama">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="<?= htmlspecialchars($data['nama']) ?>"
                        minlength="3"
                        maxlength="100"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="nim">
                        NIM
                    </label>

                    <input
                        type="text"
                        id="nim"
                        name="nim"
                        value="<?= htmlspecialchars($data['nim']) ?>"
                        minlength="5"
                        maxlength="20"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="alamat">
                        Alamat
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        minlength="3"
                        maxlength="150"
                        required
                    ><?= htmlspecialchars($data['alamat']) ?></textarea>

                </div>


                <div class="form-actions">

                    <a
                        href="index.php"
                        class="btn btn-light"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>