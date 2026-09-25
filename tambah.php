<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Mahasiswa</title>

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
                CREATE DATA
            </span>

            <h1>
                Tambah Mahasiswa
            </h1>

            <p>
                Masukkan informasi mahasiswa
                dengan lengkap.
            </p>


            <form
                method="POST"
                action="proses_tambah.php"
            >

                <div class="form-group">

                    <label for="nama">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Contoh: Dheila Yosa Chintaka"
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
                        placeholder="Contoh: 251234567"
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
                        placeholder="Masukkan alamat mahasiswa"
                        minlength="3"
                        maxlength="150"
                        required
                    ></textarea>

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
                        Simpan Data
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>