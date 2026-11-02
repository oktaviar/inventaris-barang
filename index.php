<?php
require_once "koneksi.php";

$query = "SELECT * FROM barang ORDER BY id DESC";
$result = $conn->query($query);

$total_barang = $conn
    ->query("SELECT COUNT(*) AS total FROM barang")
    ->fetch_assoc()['total'];

$total_stok = $conn
    ->query("SELECT SUM(stok) AS total FROM barang")
    ->fetch_assoc()['total'] ?? 0;

$total_kategori = $conn
    ->query("SELECT COUNT(DISTINCT kategori) AS total FROM barang")
    ->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Inventaris Barang</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="brand">
            INVENT<span>ORY.</span>
        </div>

        <div class="menu-label">
            Menu Utama
        </div>

        <nav class="menu">

            <a
                href="index.php"
                class="active"
            >
                🏠 Dashboard
            </a>

            <a href="index.php">
                📦 Data Barang
            </a>

            <a href="tambah.php">
                ➕ Tambah Barang
            </a>

        </nav>

    </aside>

    <main class="main">

        <div class="topbar">

            <div>

                <h1>
                    Dashboard
                </h1>

                <p class="subtitle">
                    Selamat datang di Sistem Inventaris Barang
                </p>

            </div>

            <div class="admin">
                👤 Administrator
            </div>

        </div>

        <section class="stats">

            <div class="stat-card">

                <div class="stat-icon">
                    📦
                </div>

                <p>
                    Total Jenis Barang
                </p>

                <h2>
                    <?= $total_barang; ?>
                </h2>

                <small style="color: #64748b;">
                    Data barang tersimpan
                </small>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    📊
                </div>

                <p>
                    Total Stok Barang
                </p>

                <h2>
                    <?= $total_stok; ?>
                </h2>

                <?php if ($total_stok <= 10): ?>

                    <small style="color: #dc2626;">
                        ⚠ Stok perlu diperiksa
                    </small>

                <?php else: ?>

                    <small style="color: #16a34a;">
                        ✓ Stok tersedia
                    </small>

                <?php endif; ?>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    🗂️
                </div>

                <p>
                    Total Kategori
                </p>

                <h2>
                    <?= $total_kategori; ?>
                </h2>

                <small style="color: #64748b;">
                    Kategori barang
                </small>

            </div>

        </section>

        <section class="content-card">

            <div class="card-heading">

                <div>

                    <h2>
                        Daftar Barang
                    </h2>

                    <p class="subtitle">
                        Data inventaris yang tersimpan
                    </p>

                </div>

                <a
                    href="tambah.php"
                    class="btn btn-primary"
                >
                    + Tambah Barang
                </a>

            </div>

            <div class="toolbar">

                <input
                    type="text"
                    class="search"
                    id="searchInput"
                    placeholder="Cari nama, kode, atau kategori barang..."
                >

            </div>

            <table id="barangTable">

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Kode Barang
                        </th>

                        <th>
                            Nama Barang
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th>
                            Stok
                        </th>

                        <th>
                            Kondisi
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if ($result && $result->num_rows > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($barang = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['kode_barang']
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['nama_barang']
                                    ); ?>
                                </td>


                                <!-- KATEGORI -->
                                <td>
                                    <?= htmlspecialchars(
                                        $barang['kategori']
                                    ); ?>
                                </td>


                                <!-- STOK -->
                                <td>

                                    <?php if ($barang['stok'] <= 5): ?>

                                        <span style="
                                            color: #dc2626;
                                            font-weight: 700;
                                        ">
                                            <?= htmlspecialchars(
                                                $barang['stok']
                                            ); ?>

                                            ⚠
                                        </span>

                                    <?php else: ?>

                                        <?= htmlspecialchars(
                                            $barang['stok']
                                        ); ?>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (
                                        $barang['kondisi'] === 'Baik'
                                    ): ?>

                                        <span class="badge">
                                            <?= htmlspecialchars(
                                                $barang['kondisi']
                                            ); ?>
                                        </span>

                                    <?php else: ?>

                                        <span
                                            style="
                                                background: #fef3c7;
                                                color: #92400e;
                                                padding: 6px 10px;
                                                border-radius: 20px;
                                                font-size: 11px;
                                            "
                                        >
                                            <?= htmlspecialchars(
                                                $barang['kondisi']
                                            ); ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- AKSI -->
                                <td>

                                    <div class="actions">

                                        <a
                                            href="edit.php?id=<?= $barang['id']; ?>"
                                            class="btn btn-edit"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $barang['id']; ?>"
                                            class="btn btn-delete"
                                            onclick="return confirm(
                                                'Yakin ingin menghapus barang ini?'
                                            )"
                                        >
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="7"
                                class="empty"
                            >
                                Belum ada data barang.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </section>

    </main>

</div>


<script>

const searchInput =
    document.getElementById("searchInput");

const rows =
    document.querySelectorAll(
        "#barangTable tbody tr"
    );

searchInput.addEventListener(
    "keyup",
    function () {

        const keyword =
            this.value.toLowerCase();

        rows.forEach(function (row) {

            const text =
                row.textContent.toLowerCase();

            if (text.includes(keyword)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    }
);

</script>

</body>

</html>