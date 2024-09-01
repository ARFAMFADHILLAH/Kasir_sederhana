<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../pegawai/dashboard.css"> 

    <title>Pengaturan</title>
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
            <li class="active">
                <a href="pengaturan.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Pengaturan</span>
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
                <img src="img/My Selfie Character (head animation).jpg">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Pengaturan</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="pengaturan.php">Pengaturan</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="settings">
                <div class="form-section">
                    <h3>Profil Toko</h3>
                    <div class="form-group">
                        <label for="username">Nama Toko</label> : DLH.STORE
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label> : 0813 - 8578 - 3587
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label> : Jalan syafiul ikhwan No.27S
                    </div>
                </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
</body>
</html>
