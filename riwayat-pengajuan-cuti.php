<?php
$halaman = 'Riwayat Pengajuan Cuti';
require_once('./head.php');
require_once('./function/PengajuanCuti.php');
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
$rows = PengajuanCuti('', []);
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
            <h3 class="card-title">Daftar</h3>
          </div>
          <div class="card-body p-3">
            <table id="main-table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 30px">No</th>
                  <th style="width: 40px">Aksi</th>
                  <th style="width: 120px">Tanggal Pengajuan</th>
                  <th style="width: 120px">Unit Kerja</th>
                  <th style="width: 95px">Jabatan</th>
                  <th style="width: 95px">Nama Pegawai</th>
                  <th style="width: 50px">Status Pengajuan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td>
                      <a class="btn btn-sm btn-primary" href="./detail-pengajuan-cuti?id=<?= $row['id_pengajuan'] ?>"><i class="fas fa-eye"></i></a>
                    </td>
                    <td><?= $fmt->format(strtotime($row['tanggal_modifikasi'])) ?></td>
                    <td><?= $row['nama_unitkerja'] ?></td>
                    <td><?= $row['nama_jabatan'] ?></td>
                    <td><?= $row['nama_pegawai'] ?></td>
                    <td>
                      <div class="bg-<?= $row['status_pengajuan'] === 'Proses' ? "warning" : ($row['status_pengajuan'] === 'Disetujui' ? "success" : ($row['status_pengajuan'] === 'Tidak Disetujui' ? "danger" : "secondary")) ?> rounded-pill text-center mx-3 py-1">
                        <?= $row['status_pengajuan'] ?>
                      </div>
                    </td>
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
<script>
  $(document).ready(() => {
    <?php if (isset($_SESSION['flash']['type'])) { ?>
      <?php if ($_SESSION['flash']['type'] === 'ADD') { ?>
        $('#modal-add').modal('show');
      <?php } else { ?>
        $('#modal-edit').modal('show');
      <?php } ?>
    <?php } ?>
  })
</script>
<?php
require_once('./foot.php');
?>