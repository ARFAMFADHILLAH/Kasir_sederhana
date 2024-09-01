<?php
require '../functions.php';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>';
    } elseif ($_GET['status'] == 'error') {
        echo '<div class="alert alert-danger" role="alert">Terjadi kesalahan saat menghapus data!</div>';
    }
}

// Check if the ID parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the ID from the URL and escape it for safety
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Perform the delete operation
    if (mysqli_query($conn, "DELETE FROM barang WHERE id = '$id'")) {
        // Redirect with a success message
        header("Location: databarang.php?status=success");
    } else {
        // Redirect with an error message if something went wrong
        header("Location: databarang.php?status=error");
    }
} else {
    // Redirect to the list page if no ID is provided
    header("Location: datapelanggan.php?status=error");
}

exit();
?>
