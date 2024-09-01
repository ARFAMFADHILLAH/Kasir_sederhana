<?php
require '../functions.php';


if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>';
    } elseif ($_GET['status'] == 'error') {
        echo '<div class="alert alert-danger" role="alert">Terjadi kesalahan saat mengubah data!</div>';
    }
}

// Get the data sent from the modal edit pelanggan
$id = $_POST['id'];
$namabarang = $_POST['nama'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$kode = $_POST['kode_barang'];
$expired = $_POST['expired'];

// Update data and check if the query was successful
if (mysqli_query($conn, "UPDATE barang SET nama = '$namabarang', harga = '$harga', stok = '$stok', kode_barang = '$kode', expired = '$expired' WHERE id = $id")) {
    // Redirect with a success message
    header("Location: databarang.php?status=success");
} else {
    // Redirect with an error message if something went wrong
    header("Location: databarang.php?status=error");
}
exit();
?>