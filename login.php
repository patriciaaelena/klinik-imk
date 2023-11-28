<?php
$halaman = 'Login';
require_once('function.php');
if (isset($_SESSION['auth'])) {
  header("Location: ./");
  die;
}
if (isset($_POST['login'])) {
  Auth('LOGIN', $_POST);
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
  <title>Login | Sistem Informasi Cuti</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.css">
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.css">
  <link rel="stylesheet" href="./dist/css/adminlte.css">
  <!-- <link rel="stylesheet" href="./plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> -->
  <link rel="stylesheet" href="./plugins/sweetalert2/sweetalert2.css">
</head>

<body class="hold-transition login-page">
  <div class="custom-login-page">
    <div class="login-box m-auto">
      <div class="login-logo">
        <img src="./dist/img/upr.png" alt="UPR Logo" class="drop-shadow" style="opacity: .8" width="100" height="100">
        <br>
        <span class="">SISTEM INFORMASI CUTI</span>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Silahkan Masukkan NIP/NIK dan Password untuk Login</p>
          <form method="post" class="needs-validation" novalidate>
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="NIP / NIK" name="username" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Password" name="password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="./plugins/jquery/jquery.js"></script>
  <script src="./plugins/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="./dist/js/adminlte.js"></script>
  <script src="./plugins/sweetalert2/sweetalert2.js"></script>
  <script>
    (function() {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function(form) {
          form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })();
    <?php if (isset($_SESSION['flash'])) { ?>
      $(document).ready(() => {
        Swal.fire({
          icon: "<?= $_SESSION['flash']['status'] ?>",
          text: "<?= $_SESSION['flash']['msg'] ?>",
          timer: 3000
        });
      })
    <?php } ?>
  </script>
</body>

</html>
<?php
unset($_SESSION['flash']);
?>