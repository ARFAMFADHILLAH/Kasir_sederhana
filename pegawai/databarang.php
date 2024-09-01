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

if (isset($_POST["submit"])) {
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";
    
    if (tambahbarang($_POST) > 0) {
        echo "
        <script>
        alert('Data berhasil ditambahkan!');
        document.location.href='databarang.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Data gagal ditambahkan!');
        document.location.href='databarang.php';
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

	<title>Barang</title>
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
		 <!-- MAIN -->
		 <main>
            <div class="head-title">
                <div class="left">
                    <h1>Daftar Barang</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="databarang.php">Barang</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="dashboard.php">Dashboard</a>
                        </li>
                    </ul>
                </div>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                    <i class='bx bx-add-to-queue'> Tambah</i>
                </button>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Expired</th>
                                <th>Kode barang</th>
								<th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
						<?php
$dt_barang = mysqli_query($conn, "SELECT * FROM barang");
$no = 1;

while ($barang = mysqli_fetch_assoc($dt_barang)) {
?>
<tr>
    <td><?= $no++; ?></td>
    <td><img src="./barang/<?= htmlspecialchars($barang['gambar']); ?>" width="100" alt="Gambar Barang"></td>
    <td><?= htmlspecialchars($barang['nama']); ?></td>
	<td>Rp. <?= number_format(htmlspecialchars($barang['harga']), 0, ',', '.'); ?></td>
    <td><?= htmlspecialchars($barang['stok']); ?></td>
    <td><?= htmlspecialchars($barang['expired']); ?></td>
    <td><?= htmlspecialchars($barang['kode_barang']); ?></td>
    <td>
        <!-- Trigger Modal -->
        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#edit-produk<?= $barang['id']; ?>">
            <i class='bx bxs-edit'></i>
        </a>
		<a href="hapusbarang.php?id=<?= htmlspecialchars($barang['id']); ?>" class="btn btn-danger" role="button" title="hapus"><i class='bx bxs-trash'></i></a>
    </td>
</tr>
<?php
}
?>

<!-- Modal Edit (Place this outside of the loop) -->
<?php

mysqli_data_seek($dt_barang, 0); 
while ($barang = mysqli_fetch_assoc($dt_barang)) {
?>
<div class="modal fade" id="edit-produk<?= $barang['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="edit-produkLabel<?= $barang['id']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="edit-produkLabel<?= $barang['id']; ?>">Edit Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="editbarang.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama :</label>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($barang['id']); ?>">
                        <input type="text" id="nama" name="nama" class="form-control" required value="<?= htmlspecialchars($barang['nama']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga :</label>
                        <input type="number" id="harga" name="harga" class="form-control" required value="<?= htmlspecialchars($barang['harga']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok :</label>
                        <input type="number" id="stok" name="stok" class="form-control" required value="<?= htmlspecialchars($barang['stok']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="expired">Expired :</label>
                        <input type="date" id="expired" name="expired" class="form-control" required value="<?= htmlspecialchars($barang['expired']); ?>">
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
                </form>
        </div>
    </div>
</div>
<?php
}
?>



<!-- Modal tambah -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama :</label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga :</label>
                        <input type="number" id="harga" name="harga" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok :</label>
                        <input type="number" id="stok" name="stok" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="expired">Expired :</label>
                        <input type="date" id="expired" name="expired" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="kode_barang">Kode Barang :</label>
                        <input type="number" id="kode_barang" name="kode_barang" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Upload Gambar:</label>
                        <input type="file" id="gambar" name="gambar" class="form-control-file">
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
                </form>
        </div>
    </div>
</div>
</body>
</html>
