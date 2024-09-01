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
$namapelanggan = $_POST['nama'];
$email = $_POST['email'];
$notelepon = $_POST['notelepon'];
$role = $_POST['role'];

// Update data and check if the query was successful
if (mysqli_query($conn, "UPDATE user SET nama = '$namapelanggan', email = '$email', no_hp = '$notelepon', role = '$role' WHERE id = $id")) {
    // Redirect with a success message
    header("Location: datapegawai.php?status=success");
} else {
    // Redirect with an error message if something went wrong
    header("Location: datapegawai.php?status=error");
}
exit();
?>
