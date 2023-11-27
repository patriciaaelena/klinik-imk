<?php
$halaman = 'Detail Pegawai';
require_once('./head.php');
require_once('./function/Jabatan.php');
require_once('./function/Pegawai.php');
require_once('./function/Kalkulasi.php');
require_once('./function/PengajuanCuti.php');
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ./pegawai");
  die;
}
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
$single = Pegawai('GET', ['id_pegawai' => $_GET['id']]);
$rows = PengajuanCuti('', ['id_pegawai' => $_GET['id']]);
if ($single === false) {
  $_SESSION['flash'] = [
    'status' => 'error',
    'msg' => 'Pegawai tidak ditemukan!',
  ];
  header("Location: ./pegawai");
  die;
}
$tahunan = Kalkulasi('TAHUNAN-FORM', [
  'id_pegawai' => $_GET['id'],
  'tanggal_modifikasi' => date("Y-m-d"),
]);
$lain = Kalkulasi('OTHER-FORM', [
  'id_pegawai' => $_GET['id'],
  'tanggal_modifikasi' => date("Y-m-d"),
]);
?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $halaman ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="./pegawai">Pegawai</a>
          </li>
          <?php $halaman = (empty($single['nip']) ? "NIK." : "NIP.") . " " . (empty($single['nip']) ? $single['nik'] : $single['nip']); ?>
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
      <div class="col-12 col-xl-6">
        <!-- Horizontal Form -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Data Diri</h3>
          </div>
          <!-- form start -->
          <div class="form-horizontal">
            <div class="card-body">
              <div class="d-flex flex-column">
                <!-- <img src="http://kkn.upr.ac.id/upload/foto/203030503101.png" class="img-thumbnail" width="200px"> -->
                <div class="col d-flex flex-column">
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">Nama</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['nama_pegawai'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">NIK</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['nik'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">NIP</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['nip'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">Jabatan</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['nama_jabatan'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">Unit Kerja</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['nama_unitkerja'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">Masa Kerja</label>
                    <div class="col-9">
                      <?php
                      $date1 = new DateTime($single['mulai_kerja']);
                      $date2 = new DateTime();
                      $interval = $date1->diff($date2);
                      $d[] = $interval->y > 0 ? $interval->y . " tahun" : "";
                      $d[] = $interval->m > 0 ? $interval->m . " bulan" : "";
                      $d[] = $interval->d > 0 ? $interval->d . " hari" : "";
                      $tgl = [];
                      foreach ($d as $v) {
                        if (!empty($v)) {
                          $tgl[] = $v;
                        }
                      }
                      ?>
                      <input type="text" class="form-control" readonly value="<?= implode(", ", $tgl) ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">No HP</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['no_hp'] ?>">
                    </div>
                  </div>
                  <div class="d-flex align-items-center form-group">
                    <label for="inputEmail3" class="form-label col-3">Status</label>
                    <div class="col-9">
                      <input type="text" class="form-control" readonly value="<?= $single['status'] ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->

      </div>
      <div class="col-12 col-xl-6">
        <!-- Horizontal Form -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">
              Catatan Cuti (per tanggal <?= $fmt->format(strtotime(date('Y-m-d'))) ?>)
            </h3>
          </div>
          <!-- form start -->
          <div class="card-body table-responsive">
            <table class="table table-striped table-hover table-bordered">
              <tbody>
                <?php for ($i = 0; $i < 5; $i++) {
                ?>
                  <tr>
                    <?php if (count($tahunan[$i]) < 3) { ?>
                      <td class="px-only-3" colspan="3"><?= $tahunan[$i][0] ?></td>
                    <?php } else { ?>
                      <?php unset($tahunan[$i]['ket']); ?>
                      <?php foreach ($tahunan[$i] as $key => $val) { ?>
                        <td class="px-only-3"><?= $val === "Keterangan" ? "Jumlah Cuti" : $val ?><?= in_array($key, ['sisa', 'jml'])  ? " hari" : "" ?></td>
                      <?php } ?>
                    <?php } ?>
                    <?php foreach ($lain[$i] as $key => $val) { ?>
                      <td class="px-only-3"><?= $val ?><?= $key === "jml_hari"  ? " hari" : "" ?></td>
                    <?php } ?>
                  </tr>
                <?php
                } ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Riwayat Pengajuan Cuti</h3>
          </div>
          <div class="card-body p-3 table-responsive">
            <table id="main-table" class="table table-bordered table-striped" style="width:100%">
              <thead>
                <tr>
                  <th style="width: 30px">No</th>
                  <th style="width: 40px">Aksi</th>
                  <th style="width: 120px">Jenis Cuti</th>
                  <th style="width: 120px">Tanggal Pengajuan</th>
                  <th style="width: 120px">Mulai Cuti</th>
                  <th style="width: 120px">Lama Cuti</th>
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
                      <a class="btn btn-sm btn-primary" href="./detail-cuti-pegawai?pg=<?= $_GET['id'] ?>&id=<?= $row['id_pengajuan'] ?>"><i class="fas fa-eye"></i></a>
                    </td>
                    <td><?= $row['nama_jeniscuti'] ?></td>
                    <td><?= $fmt->format(strtotime($row['tanggal_modifikasi'])) ?></td>
                    <td><?= $fmt->format(strtotime($row['mulai_cuti'])) ?></td>
                    <td><?= $row['lama_cuti'] . " hari" ?></td>
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