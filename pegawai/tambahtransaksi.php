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
            <div class="head-title">
			<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    <i class='bx bx-add-to-queue'></i> Tambah
</a>
                </div>
				<div class="row">
    <div class="col-md-9">
        <div class="table-data">
            <div class="order">
                <div class="head">
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php
// Lakukan query untuk mengambil detail transaksi
$dt_transaksi = mysqli_query($conn, "
    SELECT d.id_barang, d.id_transaksi, b.nama AS nama_barang, d.JumlahProduk, d.Subtotal
    FROM detail_transaksi d
    JOIN barang b ON d.id_barang = b.id
");

// Periksa apakah kueri berhasil
if (!$dt_transaksi) {
    die('Query Error: ' . mysqli_error($conn));
}

$no = 1;
while ($transaksi = mysqli_fetch_array($dt_transaksi)) {
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($transaksi['nama_barang']); ?></td>
        <td><?= htmlspecialchars($transaksi['JumlahProduk']); ?></td>
        <td>Rp. <?= number_format(htmlspecialchars($transaksi['Subtotal']), 0, ',', '.'); ?></td>
        <td>
            <a href="hapustransaksi.php?barang=<?= $transaksi['id_barang']; ?>&id_transaksi=<?= $transaksi['id_transaksi']; ?>" 
               class="btn btn-danger" role="button" title="hapus">
               <i class='bx bxs-trash'></i>
            </a> 
        </td>
    </tr>
    <?php
}
?>
						<tfoot>
						<?php
// Ambil ID transaksi terbaru
$dt_transaksi = mysqli_query($conn, "SELECT MAX(id_transaksi) AS id_transaksi FROM transaksi");
$transaksi = mysqli_fetch_assoc($dt_transaksi);

// Buat kode transaksi baru
if ($transaksi && !empty($transaksi['id_transaksi'])) {
    $kode_transaksi = $transaksi['id_transaksi'];
    $urutan = (int) substr($kode_transaksi, -4, 4);
    $urutan++;
    $huruf = date('ymd');
    $kode_barang = $huruf . sprintf("%04s", $urutan);
} else {
    $kode_barang = date('ymd') . sprintf("%04s", 1);
}

// Tetapkan kode transaksi baru ke $notrans
$notrans = $kode_barang;


$idbarang = ''; 

// Kueri untuk menghitung subtotal transaksi
$subtotal_belanja = mysqli_query($conn, "SELECT SUM(Subtotal) AS subtotal FROM detail_transaksi WHERE id_transaksi = '$kode_transaksi'");

$total = 0; // Inisialisasi total

// Ambil jumlah totalnya
if ($subtotal_belanja) {
    $total_data = mysqli_fetch_assoc($subtotal_belanja);
    if (!is_null($total_data['subtotal'])) {
        $total = $total_data['subtotal'];
    }
} else {
    echo "Error fetching subtotal: " . mysqli_error($conn);
}
?>


<tr>
    <td colspan="3">Total Belanja</td>
    <td colspan="2"><strong><?= "Rp. " . number_format($total, 2, ',', '.') . ",-"; ?></strong></td>
</tr>
</tfoot>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	<form action="transaksi_proses.php" method="POST">
    <div class="simple-form-container">
        <div class="simple-form-box">
            <div class="simple-form-body">
                <div class="simple-form-group">
                    <label for="TotalHarga">Total Harga</label>
                    <input type="text" id="TotalHarga" name="TotalHarga" class="simple-form-control" value="<?= htmlspecialchars(number_format($total, 2, ',', '.')) ?>" readonly>
                </div>
                <div class="simple-form-group">
                    <label for="tanggaltransaksi">Tanggal</label>
                    <input type="date" id="tanggaltransaksi" name="tanggaltransaksi" class="simple-form-control" value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="simple-form-group">
                    <?php
                    $dt_transaksi = mysqli_query($conn, "SELECT max(id_transaksi) as id_transaksi FROM transaksi");
                    $transaksi = mysqli_fetch_array($dt_transaksi);

                    if ($transaksi && !empty($transaksi['id_transaksi'])) {
                        $kode_transaksi = $transaksi['id_transaksi'];
                        $urutan = (int) substr($kode_transaksi, -4, 4);
                        $urutan++;
                        $huruf = date('ymd');
                        $kode_transaksi = $huruf . sprintf("%04s", $urutan);
                    } else {
                        $kode_transaksi = date('ymd') . sprintf("%04s", 1);
                    }
                    ?>
                    <label for="id_transaksi">Nomor Transaksi</label>
                    <input type="text" id="id_transaksi" name="id_transaksi" class="simple-form-control" value="<?= $kode_transaksi ?>" readonly>
                </div>
                <div class="simple-form-group">
                    <label for="user_nama">Nama Pegawai</label>
                    <?php
                    $userid = $_SESSION['id'];
                    $dt_user = mysqli_query($conn, "SELECT * FROM user WHERE id = '$userid'");
                    if ($user = mysqli_fetch_array($dt_user)) { ?>
                        <input type="hidden" name="id_pegawai" value="<?= htmlspecialchars($user['id']) ?>">
                        <input type="text" class="simple-form-control" value="<?= htmlspecialchars($user['nama']) ?>" readonly>
                    <?php
                    }
                    ?>
                </div>
                <div class="simple-form-group">
                    <label for="pelanggan">Pilih Pelanggan</label>
                    <select name="id_pelanggan" id="pelanggan" class="simple-form-control" required>
                        <option value="">-- pilih pelanggan --</option>
                        <?php
                        $dt_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
                        while ($pelanggan = mysqli_fetch_array($dt_pelanggan)) { ?>
                            <option value="<?= htmlspecialchars($pelanggan['id']); ?>"><?= htmlspecialchars($pelanggan['nama']); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="simple-form-button">Simpan</button>
            </div>
        </div>
    </div>
</form>


</div>






<!-- Modal -->
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
                <?php
                $dt_transaksi = mysqli_query($conn, "SELECT max(id_transaksi) as id_transaksi FROM transaksi");
                $transaksi = mysqli_fetch_array($dt_transaksi);

                // Check if $transaksi['id_transaksi'] is not empty
                if ($transaksi && !empty($transaksi['id_transaksi'])) {
                    $kode_transaksi = $transaksi['id_transaksi'];
                    $urutan = (int) substr($kode_transaksi, -4, 4);
                    $urutan++;
                    $huruf = date('ymd');
                    $kode_barang = $huruf . sprintf("%04s", $urutan);
                } else {
                    $kode_barang = date('ymd') . sprintf("%04s", 1);
                }
                ?>
                <form action="detailtransaksi_proses.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nomor Transaksi :</label>
                        <input type="text" name="id_transaksi" id="id_transaksi" class="form-control" value="<?= htmlspecialchars($kode_transaksi); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Pilih Barang :</label>
                        <select name="id_barang" id="id_barang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php
                            $dt_barang = mysqli_query($conn, "SELECT * FROM barang");
                            while ($barang = mysqli_fetch_array($dt_barang)) { ?>
                                <option value="<?= htmlspecialchars($barang['id']); ?>"><?= htmlspecialchars($barang['nama']) . " (" . $barang['stok'] . ")"; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah:</label>
                        <input type="number" id="JumlahProduk" name="JumlahProduk" class="form-control" required>
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
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
