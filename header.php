<?php
include "function.php";
$user = $_SESSION['user'];
if(!isset($halaman)){
    header("Location: 404.php");
    exit();
}
if(!isset($_SESSION['user'])){
    header('location: login.php', true, 301);
    exit();
}
if(isset($_POST['logout'])){
    auth('logout','');
    header('location: login.php', true, 301);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $halaman ?> - Klinik IMK</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
        let data = [];
    </script>
</head>

<body class="hold-transition sidebar-mini layout-boxed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user-cog"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog mr-2"></i> Ubah Password
                        </a>
                        <form method='post'>
                            <button type='submit' name='logout' class='btn btn-link dropdown-item'><i
                                    class="fas fa-sign-out-alt mr-2"></i> Keluar</button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index3.html" class="brand-link">
                <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">Klinik IMK</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $user['nama'] ?></a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link <?= $halaman=="Dashboard" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <?php if($user['id']==1){ ?>
                        <li class="nav-item">
                            <a href="pengguna.php" class="nav-link <?= $halaman=="Kelola Pengguna" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>
                                    Pengguna
                                </p>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="obat.php" class="nav-link <?= $halaman=="Data Obat" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-medkit"></i>
                                <p>
                                    Data Obat
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="obat-masuk.php" class="nav-link <?= $halaman=="Obat Masuk" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>
                                    Kelola Obat Masuk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="obat-keluar.php" class="nav-link <?= $halaman=="Obat Keluar" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>
                                    Kelola Obat Keluar
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="laporan.php" class="nav-link <?= $halaman=="Laporan" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Laporan
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>