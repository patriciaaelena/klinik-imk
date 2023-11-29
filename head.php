<?php
require_once('function.php');
if (!isset($_SESSION['auth'])) {
  header("Location: ./login");
  die;
}
$user = $_SESSION['auth'];
if (isset($_POST['logout'])) {
  Auth('LOGOUT', '');
  header("Refresh:0");
  die;
}
if (isset($_POST['password'])) {
  unset($_POST['password']);
  Auth('PASSWORD', $_POST);
  header("Refresh:0");
  die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Sistem Informasi Pengajuan Cuti di Universitas Palangka Raya">
  <meta name="name" content="Sistem Informasi Cuti">
  <meta name='og:title' content='Login | Sistem Informasi Cuti'>
  <meta name='og:image' content='./dist/img/upr.png'>
  <meta name='og:site_name' content='Sistem Informasi Cuti'>
  <meta name='og:description' content='Sistem Informasi Pengajuan Cuti di Universitas Palangka Raya'>
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
  <div class="modal fade" id="change-password" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <div class="modal-body">
            <div class="col pb-2">
              <label for="password-lama" class="form-label">Password Lama <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password-lama" name="password-lama" required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-2">
              <label for="password-baru" class="form-label">Password Baru <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password-baru" name="password-baru" required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-2">
              <label for="ulangi-baru" class="form-label">Ulangi Password Baru <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="ulangi-baru" name="ulangi-baru" required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="password">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="prevent-non-desktop flex-column justify-content-center align-items-center gap-5">
    <img class="animation__wobble drop-shadow" src="./dist/img/upr.png" alt="UPRLogo" height="100" width="100">
    <div>
      <h3 class="text-center">Eiiittttsss gabisa, Hehehe.</h3>
      <h3 class="text-center">Pake desktop yaaa...</h3>
    </div>
  </div>
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark bg-lightblue">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center">
          <strong><?= $fmt->format(strtotime(date('Y-m-d'))) ?></strong>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="./" class="brand-link bg-lightblue">
        <img src="./dist/img/upr.png" alt="UPR Logo" class="brand-image drop-shadow" style="opacity: .8">
        <span class="brand-text font-weight-light drop-shadow">SISTEM INFORMASI CUTI</span>
      </a>
      <div class="sidebar">
        <li class="nav-item dropdown" style="list-style-type: none;">
          <a class="nav-link py-0" data-toggle="dropdown" href="#">
            <div class="user-panel d-flex align-items-center flex-column pt-3 pb-2">
              <img src="./dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" width="80px">
              <div class="info flex-1">
                <div class="d-flex flex-column text-center">
                  <div>
                    <?= $_SESSION['auth']['nama_pegawai'] ?>
                  </div>
                  <?php if (isset($_SESSION['auth']['nik'])) { ?>
                    <div>
                      <?= $_SESSION['auth']['nip'] != NULL ? "NIP. " . $_SESSION['auth']['nip'] : "NIK. " . $_SESSION['auth']['nik'] ?>
                    </div>
                  <?php } else if ($_SESSION['auth']['role'] == "1") { ?>
                    <?php if ($_SESSION['auth']['id_induk'] !== NULL) { ?>
                      <div>
                        <?= $_SESSION['auth']['nama_induk'] ?>
                      </div>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </div>
          </a>
          <?php if ($_SESSION['auth']['role'] == "1") { ?>
            <?php if (count($_SESSION['auth']['child']) > 0) { ?>
              <a class="nav-link py-0">
                <div class="user-panel d-flex align-items-center flex-column pt-2 pb-2">
                  <?php foreach ($_SESSION['auth']['child'] as $row) { ?>
                    <div><?= "$row[nama_unitkerja] $row[nama_induk]" ?></div>
                  <?php } ?>
                </div>
              </a>
            <?php } ?>
          <?php } ?>
          <div class="dropdown-menu">
            <button class="dropdown-item text-dark btn btn-link" data-toggle="modal" data-target="#change-password">Ubah Password</button>
            <button class="dropdown-item text-dark btn btn-link" onclick="handleLogout()">Keluar</button>
          </div>
        </li>
        <form method="post" id="logout-form" class="d-none">
          <input type="hidden" name="logout">
          <button type="button" class="btn btn-link text-light col-12">
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