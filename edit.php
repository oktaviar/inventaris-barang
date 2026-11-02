<?php
require_once "koneksi.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$barang = $result->fetch_assoc();

$stmt->close();

if (!$barang) {
    header("Location: index.php");
    exit;
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $kode_barang = trim($_POST["kode_barang"]);
    $nama_barang = trim($_POST["nama_barang"]);
    $kategori = trim($_POST["kategori"]);
    $stok = (int) $_POST["stok"];
    $kondisi = trim($_POST["kondisi"]);

    if (
        $kode_barang === "" ||
        $nama_barang === "" ||
        $kategori === "" ||
        $kondisi === ""
    ) {
        $pesan = "Semua data wajib diisi.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE barang
             SET kode_barang = ?,
                 nama_barang = ?,
                 kategori = ?,
                 stok = ?,
                 kondisi = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssisi",
            $kode_barang,
            $nama_barang,
            $kategori,
            $stok,
            $kondisi,
            $id
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal memperbarui data barang.";
        }

        $stmt->close();
    }
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

    <title>Edit Barang - Inventory</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

    <style>

        .form-card {
            background: white;
            border-radius: 13px;
            padding: 28px;
            max-width: 800px;
            box-shadow: 0 3px 15px rgba(0,0,0,.035);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #3b82f6;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .alert {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        @media (max-width: 600px) {

            .form-row {
                grid-template-columns: 1fr;
            }

        }

    </style>

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

            <a href="index.php">
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

                <h1>Edit Barang</h1>

                <p class="subtitle">
                    Perbarui informasi barang
                </p>

            </div>

            <div class="admin">
                👤 Administrator
            </div>

        </div>


        <div class="form-card">

            <div class="card-heading">

                <div>

                    <h2>
                        Form Edit Barang
                    </h2>

                    <p class="subtitle">
                        Ubah data barang yang diperlukan
                    </p>

                </div>

            </div>


            <?php if ($pesan !== ""): ?>

                <div class="alert">

                    <?= htmlspecialchars($pesan); ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <div class="form-row">

                    <div class="form-group">

                        <label for="kode_barang">
                            Kode Barang
                        </label>

                        <input
                            type="text"
                            id="kode_barang"
                            name="kode_barang"
                            class="form-control"
                            value="<?= htmlspecialchars($barang['kode_barang']); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="nama_barang">
                            Nama Barang
                        </label>

                        <input
                            type="text"
                            id="nama_barang"
                            name="nama_barang"
                            class="form-control"
                            value="<?= htmlspecialchars($barang['nama_barang']); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="kategori">
                            Kategori
                        </label>

                        <select
                            id="kategori"
                            name="kategori"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Kategori --
                            </option>

                            <option
                                value="Elektronik"
                                <?= $barang['kategori'] === 'Elektronik' ? 'selected' : ''; ?>
                            >
                                Elektronik
                            </option>

                            <option
                                value="Aksesoris"
                                <?= $barang['kategori'] === 'Aksesoris' ? 'selected' : ''; ?>
                            >
                                Aksesoris
                            </option>

                            <option
                                value="Peralatan"
                                <?= $barang['kategori'] === 'Peralatan' ? 'selected' : ''; ?>
                            >
                                Peralatan
                            </option>

                            <option
                                value="ATK"
                                <?= $barang['kategori'] === 'ATK' ? 'selected' : ''; ?>
                            >
                                ATK
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="stok">
                            Jumlah Stok
                        </label>

                        <input
                            type="number"
                            id="stok"
                            name="stok"
                            class="form-control"
                            min="0"
                            value="<?= htmlspecialchars($barang['stok']); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="kondisi">
                        Kondisi Barang
                    </label>

                    <select
                        id="kondisi"
                        name="kondisi"
                        class="form-control"
                        required
                    >

                        <option value="">
                            -- Pilih Kondisi --
                        </option>

                        <option
                            value="Baik"
                            <?= $barang['kondisi'] === 'Baik' ? 'selected' : ''; ?>
                        >
                            Baik
                        </option>

                        <option
                            value="Rusak Ringan"
                            <?= $barang['kondisi'] === 'Rusak Ringan' ? 'selected' : ''; ?>
                        >
                            Rusak Ringan
                        </option>

                        <option
                            value="Rusak Berat"
                            <?= $barang['kondisi'] === 'Rusak Berat' ? 'selected' : ''; ?>
                        >
                            Rusak Berat
                        </option>

                    </select>

                </div>


                <div class="form-actions">

                    <a
                        href="index.php"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        💾 Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>