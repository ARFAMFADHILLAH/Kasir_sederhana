<?php
require '../functions.php';

session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id'])) {
    // Redirect ke halaman login jika belum login
    header('Location: ../login.php');
    exit;
}

// Ambil informasi pengguna dari sesi
$user_name = $_SESSION['nama'];

// Inisialisasi variabel tanggal awal dan akhir
$start_date = '';
$end_date = '';

// Periksa apakah form filter sudah disubmit
if (isset($_POST['submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
}

// Query untuk mengambil data transaksi dengan filter tanggal
$query = "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan, user.nama AS nama_pegawai
    FROM transaksi 
    INNER JOIN pelanggan ON pelanggan.id = transaksi.id_pelanggan 
    INNER JOIN user ON user.id = transaksi.id_pegawai
";

// Tambahkan filter tanggal jika tanggal awal dan akhir diisi
if (!empty($start_date) && !empty($end_date)) {
    $query .= " WHERE transaksi.tanggaltransaksi BETWEEN '$start_date'";
}

// Jalankan query
$dt_transaksi = mysqli_query($conn, $query);
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

    <title>Laporan Transaksi</title>
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
                    <h1>Laporan Transaksi</h1>
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
            </div>

            <!-- Filter Form -->
            <div class="container mb-4">
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="start_date">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <button type="submit" name="submit" class="btn btn-primary mt-4">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Data -->
            <div class="container">
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <!-- Optional: Title or other header content -->
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Transaksi</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($transaksi = mysqli_fetch_array($dt_transaksi)) {
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($transaksi['id_transaksi']) ?></td>
                                    <td><?= htmlspecialchars($transaksi['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($transaksi['nama_pegawai']) ?></td>
                                    <td><?= htmlspecialchars($transaksi['tanggaltransaksi']) ?></td>
                                    <td>Rp <?= number_format($transaksi['TotalHarga'], 2, ',', '.') ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <!-- MAIN -->

    </section>
    <!-- CONTENT -->

    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
