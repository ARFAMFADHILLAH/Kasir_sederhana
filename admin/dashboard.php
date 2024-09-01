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
				

// hitung jumlah barang
$h1 = mysqli_query($conn, "SELECT * FROM barang");
$h2 = ($h1) ? mysqli_num_rows($h1) : 0; // jumlah barang

// hitung jumlah pegawai 
$pegawai = mysqli_query($conn, "SELECT * FROM user WHERE role='pegawai'");
$pegawai2 = ($pegawai) ? mysqli_num_rows($pegawai) : 0; // jumlah pegawai

// hitung jumlah pelanggan 
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$pelanggan2 = ($pelanggan) ? mysqli_num_rows($pelanggan) : 0; // jumlah pelanggan

// hitung jumlah transaksi
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi");
$transaksi2 = ($transaksi) ? mysqli_num_rows($transaksi) : 0; // jumlah transaksi

// hitung jumlah barang baru
$laporan = mysqli_query($conn, "SELECT * FROM detail_transaksi");
$laporan2 = ($laporan) ? mysqli_num_rows($laporan) : 0; // jumlah transaksi
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

	<style>
        .box-info {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.box-info li {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 10px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.box-info li:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.box-info i {
    font-size: 24px;
    color: #333;
    display: block;
    margin-bottom: 10px;
}

.box-info .text h3 {
    margin: 0;
    font-size: 18px;
}

.box-info .text p {
    margin: 5px 0 0;
    color: #666;
}

.box-info .text a {
    text-decoration: none;
    color: inherit;
}

.box-info .text a:hover {
    color: #007bff;
    text-decoration: underline;
}

    /* Animation for typing effect */
@keyframes typing {
    from { width: 0; }
    to { width: 60%; } /* Adjust the width percentage as needed */
}

.brand .text {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    font-size: 20px; /* Adjust as needed */
    animation: typing 4s steps(30, end); /* Removed blink effect */
}
    </style>

	<title>admin</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bxs-shopping-bag'></i>
        <span class="text">DLH.STORE</span>
    </a>
		<ul class="side-menu top">
			<li class="active">
				<a href="dashboard.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
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
					<h1>Dashboard</h1>
					
				</div>
			</div>

			<ul class="box-info">
    <li>
        <i class='bx bxs-wallet'></i>
        <span class="text">
            <h3><strong><?= $transaksi2 ?></strong></h3>
            <a href="datatransaksi.php">
                <p>Data Transaksi</p>
            </a>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3><strong><?= $pelanggan2 ?></strong></h3>
            <a href="datapelanggan.php">
                <p>Daftar Pelanggan</p>
            </a>
        </span>
    </li>
</ul>

<ul class="box-info">
    <li>
        <i class='bx bxs-box'></i>
        <span class="text">
            <h3><strong><?= $h2 ?></strong></h3>
            <a href="databarang.php">
                <p>Daftar Barang</p>
            </a>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
            <h3><strong><?= $pegawai2 ?></strong></h3>
            <a href="datapegawai.php">
                <p>Daftar Pegawai</p>
            </a>
        </span>
    </li>
    <li>
        <i class='bx bxs-cart'></i>
        <span class="text">
            <h3><strong><?= $laporan2 ?></strong></h3>
            <a href="transaksi_laporan.php">
                <p>Laporan Transaksi</p>
            </a>
        </span>
    </li>
</ul>


		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script src="script.js"></script>
</body>
</html>
