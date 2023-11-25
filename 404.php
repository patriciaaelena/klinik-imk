<?php
ob_end_clean();
$url = "http://localhost:8080/sitibel/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="<?= $url ?>dist/img/upr.png" rel="icon">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>404 | Sistem Informasi Cuti</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= $url ?>plugins/fontawesome-free/css/all.css">
  <link rel="stylesheet" href="<?= $url ?>dist/css/adminlte.css">
</head>

<body id="page-top">
  <div class="d-flex justify-content-center" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">
    <div class="align-self-center text-center">
      <div class="error-page">
        <h2 class="display-1 text-warning drop-shadow"> 404</h2>
        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
          <p>
            We could not find the page you were looking for.
          </p>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= $url ?>plugins/jquery/jquery.js"></script>
  <script src="<?= $url ?>plugins/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="<?= $url ?>dist/js/adminlte.js"></script>
  <script src="<?= $url ?>dist/js/demo.js"></script>
</body>

</html>