<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
require_once('./function/PengajuanCuti.php');
require_once('./function/Kalkulasi.php');
$pengajuan = PengajuanCuti('GET-ONE-SIMPLE', ['status_pengajuan' => 'Proses', 'id_pegawai' => $user['id_pegawai']]);
if ($pengajuan === NULL) {
  $pengajuan = PengajuanCuti('GET-ONE-SIMPLE', ['status_pengajuan' => 'Disetujui', 'id_pegawai' => $user['id_pegawai']]);
}
$tahunan = Kalkulasi('TAHUNAN-FORM', [
  'id_pegawai' => $user['id_pegawai'],
  'tanggal_modifikasi' => date("Y-m-d"),
]);
$lain = Kalkulasi('OTHER-FORM', [
  'id_pegawai' => $user['id_pegawai'],
  'tanggal_modifikasi' => date("Y-m-d"),
]);
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="info-box mb-3 bg-<?= $pengajuan === NULL ? "info" : ($pengajuan['status_pengajuan'] === "Proses" ? "warning" : "success") ?> justify-content-between">
        <div class="d-flex">
          <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
          <div class="info-box-content flex">
            <?php
            $msg = "Tidak Ada Pengajuan Cuti";
            if ($pengajuan !== NULL) {
              if ($pengajuan['status_pengajuan'] === "Proses") {
                $msg = "Sedang Proses Pengajuan";
              } else {
                $msg = "Pengajuan Cuti Disetujui, untuk cuti ";
                $msg .= (int)$pengajuan['lama_cuti'] < 2 ? "pada " . $fmt->format(strtotime($pengajuan['mulai_cuti']))
                  : "mulai dari " . $fmt->format(strtotime($pengajuan['mulai_cuti'])) . " hingga " . $fmt->format(strtotime($pengajuan['selesai_cuti']));
                $msg .= " ($pengajuan[lama_cuti] hari)";
              }
            }
            ?>
            <span class="info-box-text"><?= $msg ?></span>
          </div>
        </div>
        <?php if ($pengajuan === NULL || $pengajuan['status_pengajuan'] !== "Disetujui") { ?>
          <div class="d-flex align-items-center pr-3">
            <a href="./cuti" class="btn btn-primary"><?= $pengajuan === NULL ? "Ajukan Cuti" : "Periksa" ?></a>
          </div>
        <?php } ?>
        <!-- /.info-box-content -->
      </div>
    </div>
  </div>
  <!-- /.row -->
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
                    <input type="text" class="form-control" readonly value="<?= $user['nama_pegawai'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">NIK</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nik'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">NIP</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nip'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Jabatan</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nama_jabatan'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Unit Kerja</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nama_unitkerja'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Masa Kerja</label>
                  <div class="col-9">
                    <?php
                    $date1 = new DateTime($user['mulai_kerja']);
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
                    <input type="text" class="form-control" readonly value="<?= $user['no_hp'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Status</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['status'] ?>">
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
</div>