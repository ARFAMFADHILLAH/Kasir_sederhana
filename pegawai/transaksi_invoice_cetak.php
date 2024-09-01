<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            width: 58mm; /* Lebar standar struk */
            margin: auto;
        }
        .text-center {
            text-align: center;
            display: block;
            margin: 0 auto;
        }
        .container {
            padding: 10px;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .info {
            margin: 5px 0;
        }
        .info strong {
            display: inline-block;
            width: 50%;
        }
        .items {
            margin: 5px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        .items div {
            margin: 3px 0;
        }
        .items div strong {
            display: inline-block;
            width: 50%;
        }
        .thank-you {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        /* CSS untuk pencetakan */
        @media print {
            /* Hanya tampilkan elemen yang diinginkan saat mencetak */
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<?php
require '../functions.php';

session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}
?> 

<div class="container">
    <?php
    // Gunakan GET untuk mengambil ID transaksi dari URL
    if (isset($_GET['id_transaksi']) && !empty($_GET['id_transaksi'])) {
        $transaksiid = $_GET['id_transaksi'];
        
        // Query untuk mengambil detail transaksi, pelanggan, dan tanggal transaksi
        $query = "
            SELECT transaksi.id_transaksi, pelanggan.nama AS nama_pelanggan, transaksi.tanggaltransaksi
            FROM transaksi
            INNER JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id
            WHERE transaksi.id_transaksi = '$transaksiid'
        ";

        $dt_transaksi = mysqli_query($conn, $query);

        if ($dt_transaksi && mysqli_num_rows($dt_transaksi) > 0) {
            while ($transaksi = mysqli_fetch_assoc($dt_transaksi)) {
                ?>
                <h2 class="text-center"><strong>DLH.STORE</strong></h2>
                <h3 class="text-center"><strong>Jl. Syafiul Ikhwan No.27s</strong></h3>
                <h3 class="text-center"><strong>Pd. Gede, Kota Bks</strong></h3>
                <div class="line"></div>
                <div class="info">
                    <strong>No. Transaksi</strong>: <?= htmlspecialchars($transaksi['id_transaksi']); ?>
                </div>
                <div class="info">
                    <strong>Nama Pelanggan</strong>: <?= htmlspecialchars($transaksi['nama_pelanggan']); ?>
                </div>
                <div class="info">
                    <strong>Tanggal Transaksi</strong>: <?= htmlspecialchars($transaksi['tanggaltransaksi']); ?>
                </div>
                <div class="line"></div>
                <div class="items">
                    <strong>Daftar Barang:</strong>
                    <?php
                    // Query untuk mengambil detail barang dari transaksi
                    $query_barang = "
                        SELECT b.nama AS nama_barang, dt.Subtotal, dt.JumlahProduk
                        FROM detail_transaksi dt
                        INNER JOIN barang b ON dt.id_barang = b.id
                        WHERE dt.id_transaksi = '$transaksiid'
                    ";
                    $dt_barang = mysqli_query($conn, $query_barang);
                    if ($dt_barang && mysqli_num_rows($dt_barang) > 0) {
                        while ($barang = mysqli_fetch_assoc($dt_barang)) {
                            ?>
                            <div>
                                <strong>Nama Barang</strong>: <?= htmlspecialchars($barang['nama_barang']); ?>
                            </div>
                            <div>
                                <strong>Jumlah</strong>: <?= htmlspecialchars($barang['JumlahProduk']); ?>
                            </div>
                            <div>
                                <strong>Subtotal</strong>: Rp <?= number_format($barang['Subtotal'], 2, ',', '.'); ?>
                            </div>
                            <div class="line"></div>
                            <?php
                        }
                    } else {
                        echo "<div>Tidak ada barang dalam transaksi.</div>";
                    }
                    ?>
                </div>
                <div class="info">
                    <strong>Total</strong>: 
                    <?php
                    $sub_total_belanja = mysqli_query($conn, "SELECT SUM(Subtotal) AS subtotal FROM detail_transaksi WHERE id_transaksi = '$transaksiid'");
                    $total_belanja = mysqli_fetch_assoc($sub_total_belanja);
                    $total = $total_belanja['subtotal'] ?? 0;
                    echo "Rp " . number_format($total, 2, ',', '.');
                    ?>
                </div>
                <div class="line"></div>
                <?php
            }
        } else {
            echo "<p>Transaksi tidak ditemukan.</p>";
        }
    } else {
        echo "<p>ID Transaksi tidak valid.</p>";
    }
    ?>

    <p class="thank-you">Terima kasih telah berbelanja di DLH.STORE!</p>

</div>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
