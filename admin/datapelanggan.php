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

    <title>Pelanggan</title>
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
                    <h1>Daftar pelanggan</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.php">Pelanggan</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="dashboard.php">Dashboard</a>
                        </li>
                    </ul>
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
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                </tr>
            </thead>
            <tbody>
            <?php
          $dt_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
		  $no = 1;
		  
		  while ($pelanggan = mysqli_fetch_assoc($dt_pelanggan)) {
		  ?>
			  <tr>
				  <td><?= $no++; ?></td>
				  <td><img src="../admin/pelanggan_img/pelanggan.jpg" alt="Gambar" style="width: 48px; height: auto;"></td>
				  <td><?= htmlspecialchars($pelanggan['nama']); ?></td>
				  <td><?= htmlspecialchars($pelanggan['email']); ?></td>
				  <td><?= htmlspecialchars($pelanggan['no_hp']); ?></td>
				  <td>
				 

<!-- Modal edit -->
<div class="modal fade" id="edit-pelanggan<?= $pelanggan['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit pelanggan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="editpelanggan.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama pelanggan:</label>
                        <!-- Hidden field to store the ID -->
                        <input type="hidden" name="id" value="<?= htmlspecialchars($pelanggan['id']); ?>">
                        <input type="text" id="nama" name="nama" class="form-control" required value="<?= htmlspecialchars($pelanggan['nama']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($pelanggan['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="notelepon">No hp:</label>
                        <input type="number" id="notelepon" name="notelepon" class="form-control" required value="<?= htmlspecialchars($pelanggan['no_hp']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" required value="<?= htmlspecialchars($pelanggan['alamat']); ?>">
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" name="ubah" class="btn btn-primary">Ubah</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
                </form>
        </div>
    </div>
</div>

                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah pelanggan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama pelanggan:</label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="notelepon">No hp:</label>
                        <input type="number" id="notelepon" name="notelepon" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" required>
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


<!-- Bootstrap and jQuery Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
