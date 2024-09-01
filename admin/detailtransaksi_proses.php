<?php
require '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $notrans = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
    $barang = mysqli_real_escape_string($conn, $_POST['id_barang']);
    $jumlahbarang = (int) $_POST['JumlahProduk']; // Ensure it's an integer

    // Fetch the price of the selected item
    $result = mysqli_query($conn, "SELECT harga, stok FROM barang WHERE id = '$barang'");
    
    if ($result) {
        $harga = mysqli_fetch_assoc($result);

        if ($harga && isset($harga['harga'])) {
            // Calculate the subtotal
            $subtotal2 = $harga['harga'] * $jumlahbarang;

            // Insert data into detail_transaksi
            $insert = mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_barang, JumlahProduk, Subtotal) VALUES ('$notrans', '$barang', '$jumlahbarang', '$subtotal2')");

            // Check if the insert was successful
            if ($insert) {
                // Update the stock of the selected item
                $stok = $harga['stok'] - $jumlahbarang;

                if ($stok >= 0) {
                    $update = mysqli_query($conn, "UPDATE barang SET stok='$stok' WHERE id='$barang'");
                    
                    if ($update) {
                        // Redirect to the desired page after successful insertion
                        header("Location: tambahtransaksi.php");
                        exit;
                    } else {
                        // Handle the error if update fails
                        echo "Error updating stock: " . mysqli_error($conn);
                    }
                } else {
                    // Handle the case where the stock is insufficient
                    echo "Error: Not enough stock available.";
                }
            } else {
                // Handle the error if insertion fails
                echo "Error inserting data: " . mysqli_error($conn);
            }
        } else {
            // Handle the error if the price is not found
            echo "Error: Invalid item selected.";
        }
    } else {
        // Handle the error if fetching the item price fails
        echo "Error fetching item price: " . mysqli_error($conn);
    }
} else {
    // Handle the case where the form is not submitted via POST
    header("Location: tambahtransaksi.php");
    exit;
}
?>
