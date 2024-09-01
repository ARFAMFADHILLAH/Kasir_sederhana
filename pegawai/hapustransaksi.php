<?php
require '../functions.php';

// Periksa apakah parameter GET yang diperlukan telah disetel
if (!isset($_GET['barang']) || !isset($_GET['id_transaksi'])) {
    die('Missing parameters');
}

// Mengambil dan membersihkan parameter input dengan aman
$barangid = mysqli_real_escape_string($conn, $_GET['barang']);
$transaksiid = mysqli_real_escape_string($conn, $_GET['id_transaksi']);

// Memanggil fungsi hapusTransaksi
$result = hapusTransaksi($conn, $barangid, $transaksiid);

if ($result === true) {
 // Redirect ke halaman transaksi jika berhasil
    header("location:tambahtransaksi.php");
    exit;
} else {
  // Menampilkan pesan kesalahan saat gagal
    echo $result;
}
?>
