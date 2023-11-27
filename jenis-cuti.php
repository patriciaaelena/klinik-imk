<?php
$halaman = 'Jenis Cuti';
require_once('./head.php');
require_once('./function/JenisCuti.php');
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
$rows = JenisCuti();
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
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Daftar Jenis Cuti</h3>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th style="width: 60px">No</th>
                  <th>Nama</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <th><?= $row['nama_jeniscuti'] ?></th>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
require_once('./foot.php');
?>