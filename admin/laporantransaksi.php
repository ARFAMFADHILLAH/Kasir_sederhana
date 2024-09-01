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
$pegawai = mysqli_query($conn, "SELECT * FROM user");
$pegawai2 = ($pegawai) ? mysqli_num_rows($pegawai) : 0; // jumlah pegawai

// hitung jumlah pelanggan 
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$pelanggan2 = ($pelanggan) ? mysqli_num_rows($pelanggan) : 0; // jumlah pelanggan

// hitung jumlah transaksi
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi");
$transaksi2 = ($transaksi) ? mysqli_num_rows($transaksi) : 0; // jumlah transaksi

// hitung jumlah barang baru
$laporan = mysqli_query($conn, "SELECT * FROM masuk");
$laporan2 = ($laporan) ? mysqli_num_rows($laporan) : 0; // jumlah barang baru
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

	<title>Laporan</title>
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
				<a href="stokbarang.php">
                <i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">Data transaksi</span>
				</a>
			</li>
			<li>
				<a href="pegawai.php">
                <i class='bx bx-user'></i>
					<span class="text">Tambah transaksi</span>
				</a>
			</li> 
			<li>
				<a href="pelanggan.php">
                <i class='bx bxs-user-voice'></i>
					<span class="text">Laporan transaksi</span>
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
                <span class="name"><?= htmlspecialchars($user_name) ?></span>
            </a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Laporan</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Transaksi</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="dashboard.php">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

		

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script src="script.js"></script>
</body>
</html>
