<?php
// koneksi ke db
$conn = mysqli_connect("localhost", "root", "", "kasir_sederhana");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }
    $rows = []; 
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function cari($keyword) {
  $conn = mysqli_connect("localhost", "root", "", "apk_kasir"); 
  if (!$conn) {
      die("Koneksi ke database gagal: " . mysqli_connect_error());
  }

  $keyword = htmlspecialchars($keyword);

  
  $query = "SELECT * FROM barang WHERE kode_barang = '$keyword' OR nama LIKE '%$keyword%'";

 
  $result = mysqli_query($conn, $query);


  if (!$result) {
      die("Query gagal: " . mysqli_error($conn));
  }

 
  return mysqli_fetch_assoc($result);
}



function tambahbarang($data) {
  global $conn;
  
  $nama = htmlspecialchars($data["nama"]);
  $harga = htmlspecialchars($data["harga"]);
  $stok = htmlspecialchars($data["stok"]);
  $kode_barang = htmlspecialchars($data["kode_barang"]);
  $expired = htmlspecialchars($data["expired"]);
  
  // Upload Gambar
  $gambar = uploadGambar(); // Fungsi uploadGambar harus mengembalikan nama file yang di-upload atau false
  
  if (!$gambar) {
      return false; // Jika gagal upload, kembalikan false
  }

  // Query Insert Data Barang
  $query = "INSERT INTO barang (nama, harga, stok, kode_barang, expired, gambar) 
            VALUES ('$nama', '$harga', '$stok', '$kode_barang', '$expired', '$gambar')";
  
  mysqli_query($conn, $query);
  
  return mysqli_affected_rows($conn);
}

function uploadGambar() {
  $namaFile = $_FILES['gambar']['name'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmpName = $_FILES['gambar']['tmp_name'];

  // Cek jika tidak ada gambar yang diupload
  if ($error === 4) {
      echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
      return false;
  }

  // Cek apakah yang diupload adalah gambar
  $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));

  if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
      echo "<script>alert('Yang Anda upload bukan gambar!');</script>";
      return false;
  }

  // Cek jika ukuran terlalu besar
  if ($ukuranFile > 2000000) {
      echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
      return false;
  }

  // Generate nama file baru
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensiGambar;

  // Pindahkan gambar ke folder tujuan
  move_uploaded_file($tmpName, 'barang/' . $namaFileBaru);

  return $namaFileBaru;
}

// tambah pelanggan
function tambahpelanggan($data) {
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $no_hp = htmlspecialchars($data["notelepon"]);
  $email = htmlspecialchars($data["email"]);
  $alamat = htmlspecialchars($data["alamat"]);

  // Upload Gambar
  $gambar = uploadGambar(); // Ensure this function exists and works correctly

  if (!$gambar) {
      return false; // If the upload fails, return false
  }

  $query = "INSERT INTO pelanggan (nama, no_hp, email, alamat, gambar)
            VALUES ('$nama', '$no_hp', '$email', '$alamat', '$gambar')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}
// tambah pelanggan
function tambahpegawai($data) {
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $no_hp = htmlspecialchars($data["notelepon"]);
  $email = htmlspecialchars($data["email"]);
  $role = htmlspecialchars($data["role"]);
  $password = htmlspecialchars($data["password"]);

    // Hash the password using MD5
    $hashed_password = md5($password);

  $query = "INSERT INTO user (nama, no_hp, email, password, role)
            VALUES ('$nama', '$no_hp', '$email', '$hashed_password', '$role')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function hapusTransaksi($conn, $barangid, $transaksiid) {
  // Ambil jumlah produk saat ini dari tabel detail_transaksi
  $jml_stokbaru_query = mysqli_query($conn, "SELECT JumlahProduk FROM detail_transaksi WHERE id_barang = '$barangid' AND id_transaksi = '$transaksiid'");
  if (!$jml_stokbaru_query) {
      return 'Error fetching data from detail_transaksi: ' . mysqli_error($conn);
  }
  $jml = mysqli_fetch_assoc($jml_stokbaru_query);

  // Ambil stok saat ini dari tabel barang
  $stok_query = mysqli_query($conn, "SELECT stok FROM barang WHERE id='$barangid'");
  if (!$stok_query) {
      return 'Error fetching data from barang: ' . mysqli_error($conn);
  }
  $s = mysqli_fetch_assoc($stok_query);

  // Menghitung stok baru
  $up_stok = $s['stok'] + $jml['JumlahProduk'];

  // update stok barang di table barang
  $update_stok_query = mysqli_query($conn, "UPDATE barang SET stok = '$up_stok' WHERE id = '$barangid'");
  if (!$update_stok_query) {
      return 'Error updating stock: ' . mysqli_error($conn);
  }

  // Hapus transaksi di table detail_transaksi menggunakan id_barang
  $delete_query = mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_barang = '$barangid' AND id_transaksi = '$transaksiid'");
  if (!$delete_query) {
      return 'Error deleting transaction detail: ' . mysqli_error($conn);
  }

  // Sukses
  return true;
}




// tambah detailtransaksi
if (isset($_POST['addproduk'])) {
  $idbarang = $_POST['id_barang']; // id barang kita
  $idtransaksi = $_POST['id_transaksi']; // id transaksi kita
  $qty = $_POST['qty']; // jumlah produk yang di beli sama kita cuii



  // untuk hitung stok sekarang
  $hitung1 = mysqli_query($conn, "SELECT * FROM barang where id= $idbarang");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stoksaatini = $hitung2['stok']; // stok barang saat ini yaa :)

  if ($stoksaatini>=$qty){

    // kurangi stok dengan jumlah yang di keluarkan
    $selisih = $stoksaatini-$qty;

    
   // apabila stoknya cukup
    $query = "INSERT INTO detail_transaksi (id_transaksi, id_barang, qty) VALUES ('$idtransaksi', '$idbarang', '$qty')";
    $update = mysqli_query($conn, "UPDATE barang SET stok='$selisih' WHERE id ='$idbarang'");
    $insert = mysqli_query($conn, $query);
  
    if (!$insert) {
        // Handle error
        die("Error inserting data: " . mysqli_error($conn));
    }
  } else {
     // apabila stok tidak cukup
     echo '<script>alert("Ooh.. stoknya tidak cukup");
    window.location.href="kasir.php"</script>';
  }

}




// hapus barang 
function hapusbarang($id) {
  global $conn;
  mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
  return mysqli_affected_rows($conn);
}


// ubah barang 
function ubahbarang($data) {
  global $conn;

  $id = $data["id"];
  $nama = htmlspecialchars($data["nama"]);
  $harga = htmlspecialchars($data["harga"]);
  $stok = htmlspecialchars($data["stok"]);
  $kode_barang = htmlspecialchars($data["kode_barang"]);
  $expired = htmlspecialchars($data["expired"]);
  $gambarLama = htmlspecialchars($data["gambarLama"]);

  if( $_FILES['gambar']['error'] === 4 ) {
      $gambar = $gambarLama;
  } else {
      $gambar = uploadGambar();
  }

  $query = "UPDATE barang SET
              nama = '$nama',
              harga = '$harga',
              stok = '$stok',
              kode_barang = '$kode_barang',
              expired = '$expired',
              gambar = '$gambar'
          WHERE id = $id
          ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}



function input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function register($conn)
{
  $nama = $no_hp = $email = $password = $confirm_password = $role = "";
  $nama_err = $no_hp_err = $email_err = $password_err = $confirm_password_err = $role_err = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi nama
    if (empty(trim($_POST["nama"]))) {
      $nama_err = "Please enter a name.";
    } else {
      $nama = trim($_POST["nama"]);
    }

    // Validasi nomor handphone
    if (empty(trim($_POST["no_hp"]))) {
      $no_hp_err = "Please enter a phone number.";
    } else {
      $no_hp = trim($_POST["no_hp"]);
      if (!ctype_digit($no_hp)) {
        $no_hp_err = "Please enter a valid phone number.";
      }
    }

    // Validasi email
    if (empty(trim($_POST["email"]))) {
      $email_err = "Please enter an email.";
    } else {
      $email = trim($_POST["email"]);
      // Cek jika email sudah digunakan di database
      $sql = "SELECT id FROM user WHERE email = ?";
      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "This email is already taken.";
          }
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
      }
    }

    // Validasi password
    if (empty(trim($_POST["password"]))) {
      $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
      $password_err = "Password must have at least 6 characters.";
    } else {
      $password = trim($_POST["password"]);
    }

    // Validasi confirm password
    if (empty(trim($_POST["confirm_password"]))) {
      $confirm_password_err = "Please confirm password.";
    } else {
      $confirm_password = trim($_POST["confirm_password"]);
      if (empty($password_err) && ($password != $confirm_password)) {
        $confirm_password_err = "Password did not match.";
      }
    }

    // Validasi role
    if (empty(trim($_POST["role"]))) {
      $role_err = "Please select a role.";
    } else {
      $role = trim($_POST["role"]);
    }

   // Periksa kesalahan sebelum memasukkan ke dalam database
    if (empty($nama_err) && empty($no_hp_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {
      $sql = "INSERT INTO user (nama, no_hp, email, password, role) VALUES (?, ?, ?, ?, ?)";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $no_hp, $email, $param_password, $role);
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        if (mysqli_stmt_execute($stmt)) {
          echo
          "<script>
                        alert('Registrasi berhasil silahkan login');
                        window.location.href = 'login.php';
                    </script>";
        } else {
          echo "<script>alert('Oops, tampaknya ada yang salah, tolong login kembali!')</script>";
        }
        mysqli_stmt_close($stmt);
      }
    }
  }

  return [
    'nama' => $nama,
    'no_hp' => $no_hp,
    'email' => $email,
    'password' => $password,
    'confirm_password' => $confirm_password,
    'role' => $role,
    'nama_err' => $nama_err,
    'no_hp_err' => $no_hp_err,
    'email_err' => $email_err,
    'password_err' => $password_err,
    'confirm_password_err' => $confirm_password_err,
    'role_err' => $role_err
  ];
}



function login($conn) {
  session_start();

  $email = $password = $role = "";
  $email_err = $password_err = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Validate email
      if (empty(trim($_POST["email"]))) {
          $email_err = "Tolong isi email.";
      } else {
          $email = trim($_POST["email"]);
      }

      // Validate password
      if (empty(trim($_POST["password"]))) {
          $password_err = "Tolong isi password.";
      } else {
          $password = trim($_POST["password"]);
      }

      // dapat role
      if (isset($_POST['role'])) {
          $role = $_POST['role'];
      }

     // Periksa kesalahan input sebelum menanyakan database
      if (empty($email_err) && empty($password_err)) {
          $sql = "SELECT id, nama, email, password, role FROM user WHERE email = ? AND role = ?";
          if ($stmt = mysqli_prepare($conn, $sql)) {
             // Ikat variabel ke pernyataan yang telah disiapkan sebagai parameter
              mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_role);

              // Tetapkan parameter
              $param_email = $email;
              $param_role = $role;
// Mencoba mengeksekusi pernyataan yang telah disiapkan
              if (mysqli_stmt_execute($stmt)) {
                  // Simpan hasil
                  mysqli_stmt_store_result($stmt);

                  // Periksa apakah email ada, jika ya maka verifikasi kata sandi
                  if (mysqli_stmt_num_rows($stmt) == 1) {
                     // Ikat variabel hasil
                      mysqli_stmt_bind_result($stmt, $id, $nama, $email, $hashed_password, $role);
                      if (mysqli_stmt_fetch($stmt)) {
                          if (password_verify($password, $hashed_password)) {
                            // Kata sandinya benar, jadi mulailah sesi baru
                              $_SESSION["login"] = true;
                              $_SESSION["id"] = $id;
                              $_SESSION["nama"] = $nama;
                              $_SESSION["role"] = $role;
                              setcookie("nama", $nama, time() + (86400 * 2), "/"); // Cookie valid for 2 days

                              // Arahkan pengguna ke dasbor yang sesuai berdasarkan peran mereka
                              if ($role === 'admin') {
                                  header("Location: admin/dashboard.php");
                                  exit(); // Pastikan skrip berhenti di sini
                              } elseif ($role === 'pegawai') {
                                  header("Location: pegawai/dashboard.php");
                                  exit(); // Pastikan skrip berhenti di sini
                              } else {
                                  echo "<script>alert('Role tidak dikenali!')</script>";
                              }
                              
                          } else {
                             // Menampilkan pesan kesalahan jika kata sandi tidak valid
                              $password_err = "Password yang kamu isi tidak valid.";
                          }
                      }
                  } else {
                      // Menampilkan pesan kesalahan jika email atau peran tidak ada
                      $email_err = "Tidak menemukan akun dengan email dan role tersebut.";
                  }
              } else {
                  echo "<script>alert('Oops, tampaknya ada yang salah, tolong login kembali!')</script>";
              }
              mysqli_stmt_close($stmt);
          }
      }
  }

 // Mengembalikan pesan kesalahan dan input pengguna
  return [
      'email' => $email,
      'password' => $password,
      'email_err' => $email_err,
      'password_err' => $password_err
  ];
}






  

?>
