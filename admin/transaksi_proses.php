<?php
require '../functions.php';

// Retrieve form data
$total = $_POST['TotalHarga'];
$tanggaltransaksi = date('Y-m-d H:i:s');
$no_trans = $_POST['id_transaksi'];
$userid = $_POST['id_pegawai'];
$pelangganid = $_POST['id_pelanggan'];


$query = "INSERT INTO transaksi (id_transaksi, tanggaltransaksi, TotalHarga, id_pelanggan, id_pegawai) 
          VALUES ('$no_trans', '$tanggaltransaksi', '$total', '$pelangganid', '$userid')";


if (mysqli_query($conn, $query)) {
  
    header("Location: datatransaksi.php");
} else {

    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
