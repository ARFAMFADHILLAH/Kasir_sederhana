<?php
require '../functions.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit;
}

// Retrieve user information from the session
$user_name = $_SESSION['nama'];

// Hitung jumlah barang
$h1 = mysqli_query($conn, "SELECT * FROM barang");
$h2 = ($h1) ? mysqli_num_rows($h1) : 0; // jumlah barang

// Hitung jumlah pegawai 
$pegawai = mysqli_query($conn, "SELECT * FROM user");
$pegawai2 = ($pegawai) ? mysqli_num_rows($pegawai) : 0; // jumlah pegawai

// Hitung jumlah pelanggan 
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$pelanggan2 = ($pelanggan) ? mysqli_num_rows($pelanggan) : 0; // jumlah pelanggan

// Hitung jumlah transaksi
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi");
$transaksi2 = ($transaksi) ? mysqli_num_rows($transaksi) : 0; // jumlah transaksi

// Hitung jumlah barang baru
$laporan = mysqli_query($conn, "SELECT * FROM masuk");
$laporan2 = ($laporan) ? mysqli_num_rows($laporan) : 0; // jumlah barang baru

if (isset($_POST["submit"])) {
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";
    
    if (tambahpelanggan($_POST) > 0) {
        echo "
        <script>
        alert('Data berhasil ditambahkan!');
        document.location.href='datapelanggan.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Data gagal ditambahkan!');
        document.location.href='datapelanggan.php';
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../pegawai/dashboard.css">

    <title>Transaksi</title>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-shopping-bag'></i>
            <span class="text">DLH.STORE</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
          
            <li>
                <a href="tambahtransaksi.php">
                    <i class='bx bx-user'></i>
                    <span class="text">Tambah transaksi</span>
                </a>
            </li> 
           
        </ul>
        <ul class="side-menu">
            <li>
                <a href="pengaturan.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Pengaturan</span>
                </a>
            </li>
            <li>
                <a href="../index.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="../pegawai/pegawai_img/pegawai.jpg" alt="Profile Picture">
                <span class="name"><?= htmlspecialchars($user_name) ?></span> <!-- Display user name -->
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Daftar Transaksi</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="datatransaksi.php">Transaksi</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="dashboard.php">Dashboard</a>
                        </li>
                    </ul>
                </div>
				
				<a href="tambahtransaksi.php" class="btn btn-primary">
    <i class='bx bx-add-to-queue'></i> Tambah
</a>


<div class="table-data">
    <div class="order">
        <div class="head">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal transaksi</th>
                    <th>Nama pelanggan</th>
                    <th>Nama Pegawai</th>
                    <th>Total</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <?php
$pegawai_id = $_SESSION['id'];
$dt_transaksi = mysqli_query($conn, "
    SELECT transaksi.*, pelanggan.nama AS namapelanggan, user.nama AS namapegawai, transaksi.TotalHarga AS TotalHarga 
    FROM transaksi
    INNER JOIN pelanggan ON pelanggan.id = transaksi.id_pelanggan
    INNER JOIN user ON user.id = transaksi.id_pegawai
");
$no = 1;
?>
    <tbody>
        <?php while ($transaksi = mysqli_fetch_assoc($dt_transaksi)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($transaksi['tanggaltransaksi']); ?></td>
            <td><?= htmlspecialchars($transaksi['namapelanggan']); ?></td>
            <td><?= htmlspecialchars($transaksi['namapegawai']); ?></td>
            <td>Rp <?= number_format($transaksi['TotalHarga'], 2, ',', '.'); ?></td>
            <!-- Perbaikan pada href untuk memastikan id_transaksi tertangkap dengan benar -->
            <td><a href="transaksi_invoice_cetak.php?id_transaksi=<?= htmlspecialchars($transaksi['id_transaksi']); ?>" role="button" title="cetak" class="btn btn-warning" target="_blank"><i class='bx bxs-printer'></i></a></td>
        </tr>
        <?php } ?>
    </tbody>

</table>

<!-- Bootstrap and jQuery Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
