<?php
require_once('function.php');
if (!isset($_SESSION['auth'])) {
  header("Location: ./login");
  die;
}
if (isset($_POST['logout'])) {
  Auth('LOGOUT', '');
  header("Refresh:0");
  die;
}
$user = $_SESSION['auth'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="./dist/img/upr.png" rel="icon">
  <title><?= $halaman ?> | Sistem Informasi Cuti</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.css">
  <link rel="stylesheet" href="./plugins/sweetalert2/sweetalert2.css">
  <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="./plugins/datatables-responsive/css/responsive.bootstrap4.css">
  <link rel="stylesheet" href="./plugins/select2/css/select2.css">
  <link rel="stylesheet" href="./plugins/select2-bootstrap4-theme/select2-bootstrap4.css">
  <link rel="stylesheet" href="./plugins/flatpickr/flatpickr.min.css">

  <link rel="stylesheet" href="./dist/css/adminlte.css">
  <script src="./plugins/jquery/jquery.js"></script>
  <script src="./plugins/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="./plugins/select2/js/select2.full.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark bg-lightblue">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center">
          <strong>12.00, 11/11/2023</strong>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="./" class="brand-link bg-lightblue">
        <img src="./dist/img/upr.png" alt="UPR Logo" class="brand-image drop-shadow" style="opacity: .8">
        <span class="brand-text font-weight-light drop-shadow">SISTEM INFORMASI CUTI</span>
      </a>
      <div class="sidebar">
        <form method="post" id="logout-form">
          <input type="hidden" name="logout">
          <button type="button" class="btn btn-link text-light col-12" onclick="handleLogout()">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center flex-column">
              <img src="./dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" width="80px">
              <div class="info flex-1">
                <div class="d-flex flex-column">
                  <div>
                    <?= $_SESSION['auth']['id_pegawai'] !== NULL ? $_SESSION['auth']['nama_pegawai'] : $_SESSION['auth']['username'] ?>
                  </div>
                  <?php if ($_SESSION['auth']['nik'] !== NULL) { ?>
                    <div>
                      <?= $_SESSION['auth']['nip'] != NULL ? "NIP. " . $_SESSION['auth']['nip'] : "NIK. " . $_SESSION['auth']['nik'] ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </button>
        </form>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= $halaman === 'Dashboard' ? 'javascript:void(0)' : './' ?>" class="nav-link <?= $halaman === 'Dashboard' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <?php
            switch ($_SESSION['auth']['role']) {
              case '2':
                require_once('./components/sidebar-2.php');
                break;

              default:
                require_once('./components/sidebar-0.php');
                break;
            }
            ?>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="content-wrapper">
      <!-- <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble drop-shadow" src="./dist/img/upr.png" alt="UPRLogo" height="100" width="100">
      </div> -->