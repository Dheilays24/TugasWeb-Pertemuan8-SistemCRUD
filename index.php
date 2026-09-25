<?php

session_start();

require_once "config/database.php";

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? null;

unset($_SESSION['message'], $_SESSION['message_type']);


/*
|--------------------------------------------------------------------------
| PENCARIAN
|--------------------------------------------------------------------------
*/

$keyword = trim($_GET['keyword'] ?? "");


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

// Jumlah data per halaman
$limit = 5;

// Halaman saat ini
$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

// Minimal halaman adalah 1
$page = max($page, 1);

// Menghitung posisi data
$offset = ($page - 1) * $limit;


/*
|--------------------------------------------------------------------------
| MENGHITUNG JUMLAH DATA
|--------------------------------------------------------------------------
*/

if ($keyword !== "") {

    $countQuery = $pdo->prepare(
        "SELECT COUNT(*)
         FROM mahasiswa
         WHERE nama LIKE :keyword
         OR nim LIKE :keyword
         OR alamat LIKE :keyword"
    );

    $countQuery->execute([
        ":keyword" => "%$keyword%"
    ]);

} else {

    $countQuery = $pdo->query(
        "SELECT COUNT(*) FROM mahasiswa"
    );
}

$totalData = (int) $countQuery->fetchColumn();

// Total halaman
$totalPages = max(
    1,
    (int) ceil($totalData / $limit)
);

// Jika page melebihi total halaman
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}


/*
|--------------------------------------------------------------------------
| MENGAMBIL DATA
|--------------------------------------------------------------------------
*/

if ($keyword !== "") {

    $query = $pdo->prepare(
        "SELECT *
         FROM mahasiswa
         WHERE nama LIKE :keyword
         OR nim LIKE :keyword
         OR alamat LIKE :keyword
         ORDER BY id DESC
         LIMIT :limit OFFSET :offset"
    );

    $query->bindValue(
        ":keyword",
        "%$keyword%",
        PDO::PARAM_STR
    );

} else {

    $query = $pdo->prepare(
        "SELECT *
         FROM mahasiswa
         ORDER BY id DESC
         LIMIT :limit OFFSET :offset"
    );
}

$query->bindValue(
    ":limit",
    $limit,
    PDO::PARAM_INT
);

$query->bindValue(
    ":offset",
    $offset,
    PDO::PARAM_INT
);

$query->execute();

$mahasiswa = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="page">

    <!-- HEADER -->

    <header class="header">

        <div>

            <span class="badge">
                PHP • PDO • MySQL
            </span>

            <h1>
                Data Mahasiswa
            </h1>

            <p>
                Sistem pengelolaan data mahasiswa
                berbasis CRUD.
            </p>

        </div>

        <a href="tambah.php" class="btn btn-primary">
            + Tambah Mahasiswa
        </a>

    </header>


    <!-- FLASH MESSAGE -->

    <?php if ($message): ?>

        <div class="alert <?= $message_type ?>">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <!-- STATISTIC -->

    <section class="stat-card">

        <div class="stat-icon">
            👨‍🎓
        </div>

        <div>

            <span>
                Total Mahasiswa
            </span>

            <strong>
                <?= $totalData ?>
            </strong>

        </div>

    </section>


    <!-- CONTENT -->

    <main class="card">

        <!-- SEARCH -->

        <div class="toolbar">

            <form method="GET" class="search-form">

                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Cari nama, NIM, atau alamat..."
                >

                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Cari
                </button>

                <?php if ($keyword !== ""): ?>

                    <a
                        href="index.php"
                        class="btn btn-light"
                    >
                        Reset
                    </a>

                <?php endif; ?>

            </form>

        </div>


        <!-- TABLE -->

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (count($mahasiswa) > 0): ?>

                    <?php foreach ($mahasiswa as $index => $data): ?>

                        <tr>

                            <td>
                                <?= $offset + $index + 1 ?>
                            </td>

                            <td>

                                <div class="student">

                                    <div class="avatar">
                                        <?= strtoupper(
                                            substr($data['nama'], 0, 1)
                                        ) ?>
                                    </div>

                                    <div>
                                        <strong>
                                            <?= htmlspecialchars(
                                                $data['nama']
                                            ) ?>
                                        </strong>

                                        <small>
                                            Mahasiswa
                                        </small>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <span class="nim">
                                    <?= htmlspecialchars(
                                        $data['nim']
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $data['alamat']
                                ) ?>
                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="edit.php?id=<?= $data['id'] ?>"
                                        class="action edit"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="hapus.php?id=<?= $data['id'] ?>"
                                        class="action delete"
                                        onclick="return confirm(
                                            'Yakin ingin menghapus data <?= htmlspecialchars($data['nama']) ?>?'
                                        )"
                                    >
                                        Hapus
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="5"
                            class="empty"
                        >
                            <div class="empty-icon">
                                🔍
                            </div>

                            <strong>
                                Data tidak ditemukan
                            </strong>

                            <p>
                                Belum ada data mahasiswa
                                yang sesuai dengan pencarian.
                            </p>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->

        <?php if ($totalData > 0): ?>

            <div class="pagination">

                <span>
                    Halaman <?= $page ?>
                    dari <?= $totalPages ?>
                </span>

                <div class="pagination-buttons">

                    <?php if ($page > 1): ?>

                        <a
                            href="?keyword=<?= urlencode($keyword) ?>&page=<?= $page - 1 ?>"
                            class="page-btn"
                        >
                            ←
                        </a>

                    <?php endif; ?>


                    <?php

                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);

                    for ($i = $start; $i <= $end; $i++):

                    ?>

                        <a
                            href="?keyword=<?= urlencode($keyword) ?>&page=<?= $i ?>"
                            class="page-btn
                            <?= $i == $page ? 'active' : '' ?>"
                        >
                            <?= $i ?>
                        </a>

                    <?php endfor; ?>


                    <?php if ($page < $totalPages): ?>

                        <a
                            href="?keyword=<?= urlencode($keyword) ?>&page=<?= $page + 1 ?>"
                            class="page-btn"
                        >
                            →
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>

    </main>


    <footer>

        <p>
            CRUD Data Mahasiswa • PHP PDO & MySQL
        </p>

    </footer>

</div>

</body>
</html>