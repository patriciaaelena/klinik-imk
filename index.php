<?php
$halaman = 'Dashboard';
require_once('./head.php');
?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $halaman ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><?= $halaman ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- Main content -->
<section class="content">
  <?php
  // dd($_SESSION['auth']);
  switch ($_SESSION['auth']['role']) {
    case '2':
      require_once('./components/dashboard-2.php');
      break;

    default:
      require_once('./components/dashboard-0.php');
      break;
  }
  ?>
</section>
<?php
require_once('./foot.php');
?>