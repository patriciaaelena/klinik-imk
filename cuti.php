<?php
$halaman = 'Cuti';
require_once('./head.php');
require_once('./function/Ajax.php');
require_once('./function/JenisCuti.php');
require_once('./function/TemplatePersetujuan.php');
require_once('./function/Kalkulasi.php');
require_once('./function/PengajuanCuti.php');
if ($_SESSION['auth']['role'] != '2') {
  require_once('./401.php');
  die;
}
if (isset($_POST['ajax_cek_cuti'])) {
  Ajax('CEK-JENISCUTI', $_POST);
  die;
}
if (isset($_POST['ajukan'])) {
  unset($_POST['ajukan']);
  PengajuanCuti('CREATE', $_POST);
  header("Refresh:0");
  die;
}
$rows = JenisCuti();
$userCuti = $user;
unset($userCuti['sign']);
$template = TemplatePersetujuan('GET-PERJOB', $userCuti);
$pengajuan = PengajuanCuti('GET-ONE', ['status_pengajuan' => 'Proses', 'id_pegawai' => $user['id_pegawai']]);
if ($template['id_tamplate'] !== NULL && $template['nama_pertama'] !== NULL && $template['nama_kedua'] !== NULL) {
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
  <?php
  if ($pengajuan == false) {
    $add = [];
    if (isset($_SESSION['flash']['type'])) {
      if ($_SESSION['flash']['type'] === 'ADD') {
        $add['id_jeniscuti'] = $_SESSION['flash']['data']['id_jeniscuti'];
        $add['alasan'] = $_SESSION['flash']['data']['alasan'];
        $add['mulai_cuti'] = $_SESSION['flash']['data']['mulai_cuti'];
        $add['lama_cuti'] = $_SESSION['flash']['data']['lama_cuti'];
        $add['alamat_cuti'] = $_SESSION['flash']['data']['alamat_cuti'];
      }
    }
  ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Form Pengajuan Cuti</h3>
              </div>
              <form method="POST" class="needs-validation" novalidate onsubmit="return confirm('Apakah semua data sudah benar? Data yang telah diajukan tidak dapat diubah!')">
                <div class="card-body">
                  <div class="row" id="detail-cuti">
                  </div>
                  <div class="row">
                    <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                      <label for="inputEmail3" class="col-3">Jenis Cuti <span class="text-danger">*</span></label>
                      <div class="input-group col-9">
                        <select class="custom-select form-control form-select " name="id_jeniscuti" required id="id_jeniscuti">
                          <option value="" <?= isset($add['id_jeniscuti']) ? '' : 'selected' ?> disabled> - Pilih Jenis Cuti - </option>
                          <?php foreach ($rows as $row) { ?>
                            <option value="<?= $row['id_jeniscuti'] ?>" <?= isset($add['id_jeniscuti']) ? ($row['id_jeniscuti'] == $add['id_jeniscuti'] ? 'selected' : '') : '' ?>><?= $row['nama_jeniscuti'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                      <label for="inputEmail3" class="col-3">Alasan <span class="text-danger">*</span></label>
                      <div class="col-9">
                        <textarea class="form-control" name="alasan" required><?= $add['alasan'] ?? '' ?></textarea>
                      </div>
                    </div>
                    <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                      <label for="inputEmail3" class="col-3">Mulai Tanggal <span class="text-danger">*</span></label>
                      <?php
                      $date = new DateTime();
                      $date->modify('+7 weekdays');
                      ?>
                      <div class="col-9">
                        <input type="date" class="form-control" name="mulai_cuti" id="mulai_cuti" required onkeydown="return false;" value="<?= $add['mulai_cuti'] ?? $date->format('Y-m-d') ?>">
                      </div>
                    </div>
                    <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                      <label for="inputEmail3" class="col-3">Selama <span class="text-danger">*</span></label>
                      <div class="input-group col-9">
                        <input type="number" class="form-control" name="lama_cuti" required value="<?= $add['lama_cuti'] ?? 1 ?>" id="lama_cuti" min="1">
                        <span class="input-group-text" id="basic-addon2">hari</span>
                        <div class="invalid-feedback">
                          Harus lebih dari 0
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                      <label for="inputEmail3" class="col-3">Alamat Selama Cuti <span class="text-danger">*</span></label>
                      <div class="col-9">
                        <textarea class="form-control" name="alamat_cuti" required><?= $add['alamat_cuti'] ?? '' ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col px-2 mb-3 border">
                    <table class="table text-center table-borderless table-sm">
                      <tr>
                        <td>Persetujuan Pertama</td>
                        <td>Persetujuan Kedua</td>
                      </tr>
                      <tr>
                        <td><?= $template['jabatan_pertama'] ?></td>
                        <td><?= $template['jabatan_kedua'] ?></td>
                      </tr>
                      <tr>
                        <td><?= $template['nama_pertama'] ?></td>
                        <td><?= $template['nama_kedua'] ?></td>
                      </tr>
                      <tr>
                        <td><?= $template['nip_pertama'] !== NULL ? "NIP. " . $template['nip_pertama'] : "NIK. " . $template['nip_pertama'] ?></td>
                        <td><?= $template['nip_kedua'] !== NULL ? "NIP. " . $template['nip_kedua'] : "NIK. " . $template['nip_kedua'] ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary float-right" name="ajukan">Ajukan</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
  } else {
  ?>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            <div class="info-box mb-3 bg-warning justify-content-between">
              <div class="d-flex">
                <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                <div class="info-box-content flex">
                  <span class="info-box-text">Sedang Proses Pengajuan</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="info-box mb-3 d-flex flex-column flex-grow-1 justify-content-between px-3">
              <div class="row">
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Jenis Cuti</label>
                  <div class="input-group col-9">
                    <input class="form-control" type="text" value="<?= $pengajuan['nama_jeniscuti'] ?>" readonly>
                  </div>
                </div>
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Alasan</label>
                  <div class="col-9">
                    <textarea class="form-control" readonly><?= $pengajuan['alasan'] ?? '' ?></textarea>
                  </div>
                </div>
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Mulai Tanggal</label>
                  <div class="col-9">
                    <input class="form-control" type="text" value="<?= $fmt->format(strtotime($pengajuan['mulai_cuti'])) ?>" readonly>
                  </div>
                </div>
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Sampai Tanggal</label>
                  <div class="col-9">
                    <input class="form-control" type="text" value="<?= $fmt->format(strtotime($pengajuan['selesai_cuti'])) ?>" readonly>
                  </div>
                </div>
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Selama</label>
                  <div class="input-group col-9">
                    <input class="form-control" type="text" value="<?= $pengajuan['lama_cuti'] . " hari" ?>" readonly>
                  </div>
                </div>
                <div class="form-group col-12 col-xl-6 d-flex align-items-center">
                  <label for="inputEmail3" class="col-3">Alamat Selama Cuti</label>
                  <div class="col-9">
                    <textarea class="form-control" readonly><?= $pengajuan['alamat_cuti'] ?? '' ?></textarea>
                  </div>
                </div>
              </div>
              <div class="d-flex gap-3 p-3">
                <div class="d-flex flex-column flex-grow-1">
                  <div class="col d-flex pb-3 align-items-center">
                    <div>
                      <strong>Persetujuan 1</strong>
                    </div>
                    <div class="flex-grow-1">
                      <div class="bg-<?= $pengajuan['ttd_pertama'] === NULL ? "warning" : "success" ?> rounded-pill text-center mx-3 py-1">
                        <?= $pengajuan['ttd_pertama'] === NULL ? "Proses" : "Disetujui pada " . $fmt->format(strtotime($pengajuan['ttd_pertama'])) ?>
                      </div>
                    </div>
                  </div>
                  <div class="col text-center">
                    <?= $pengajuan['jabatan_pertama'] ?>
                  </div>
                  <div class="col text-center">
                    <?= $pengajuan['nama_pertama'] ?>
                  </div>
                  <div class="col text-center">
                    <?= $template['nip_pertama'] !== NULL ? "NIP. " . $template['nip_pertama'] : "NIK. " . $template['nip_pertama'] ?>
                  </div>
                </div>
                <div class="d-flex flex-column flex-grow-1">
                  <div class="col d-flex pb-3 align-items-center">
                    <div>
                      <strong>Persetujuan 2</strong>
                    </div>
                    <div class="flex-grow-1">
                      <div class="bg-<?= $pengajuan['ttd_kedua'] === NULL ? "warning" : "success" ?> rounded-pill text-center mx-3 py-1">
                        <?= $pengajuan['ttd_kedua'] === NULL ? "Proses" : "Disetujui pada " . $fmt->format(strtotime($pengajuan['ttd_kedua'])) ?>
                      </div>
                    </div>
                  </div>
                  <div class="col text-center">
                    <?= $pengajuan['jabatan_kedua'] ?>
                  </div>
                  <div class="col text-center">
                    <?= $pengajuan['nama_kedua'] ?>
                  </div>
                  <div class="col text-center">
                    <?= $template['nip_kedua'] !== NULL ? "NIP. " . $template['nip_kedua'] : "NIK. " . $template['nip_kedua'] ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        ?>
        <div class="row">
          <div class="col">
            <div class="card p-3 no-border position-relative">
              <embed src="./form-pengajuan.php?id=<?= $pengajuan['id_pengajuan'] ?>&iframe=1" title="W3Schools Free Online Web Tutorials" height="500px" class="rounded" type="application/pdf"></embed>
              <a href="./form-pengajuan.php?id=<?= $pengajuan['id_pengajuan'] ?>" target="_blank" class="btn btn-success position-absolute"><i class="fas fa-print"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
<?php
  }
}
require_once('./foot.php');
?>