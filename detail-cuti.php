<?php
$halaman = 'Detail Pengajuan';
require_once('./head.php');
require_once('./function/TemplatePersetujuan.php');
require_once('./function/PengajuanCuti.php');
if ($_SESSION['auth']['role'] != '2') {
  require_once('./401.php');
  die;
}
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ./pegawai");
  die;
}
$template = TemplatePersetujuan('GET-PERJOB', $user);
$pengajuan = PengajuanCuti('GET-ONE', ['id_pengajuan' => $_GET['id'], 'id_pegawai' => $user['id_pegawai']]);
if ($pengajuan === NULL) {
  $_SESSION['flash'] = [
    'status' => 'error',
    'msg' => 'Data tidak ditemukan!',
  ];
  header("Location: ./riwayat-cuti");
  die;
}
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
        <div class="info-box mb-3 bg-<?= $pengajuan['status_pengajuan'] === 'Proses' ? "warning" : ($pengajuan['status_pengajuan'] === 'Disetujui' ? "success" : ($pengajuan['status_pengajuan'] === 'Tidak Disetujui' ? "danger" : "secondary")) ?> justify-content-between">
          <div class="d-flex">
            <span class="info-box-icon"><i class="fas <?= $pengajuan['status_pengajuan'] === 'Proses' ? "fa-info-circle" : ($pengajuan['status_pengajuan'] === 'Disetujui' ? "fa-check-circle" : ($pengajuan['status_pengajuan'] === 'Tidak Disetujui' ? "fa-times-circle" : "secondary")) ?>"></i></span>
            <div class="info-box-content flex">
              <span class="info-box-text"><?= $pengajuan['status_pengajuan'] === 'Proses' ? "Sedang Proses Pengajuan" : "Pengajuan $pengajuan[status_pengajuan]" ?></span>
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
          <?php if ($pengajuan['status_pengajuan'] !== 'Proses' && $pengajuan['status_pengajuan'] !== 'Disetujui') { ?>
            <div class="row">
              <div class="form-group col-12 d-flex align-items-center">
                <label for="inputEmail3" class="col-3">Alasan</label>
                <div class="col-9">
                  <textarea class="form-control" readonly><?= $pengajuan['alasan'] ?? '' ?></textarea>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php
    // dd($pengajuan);
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
require_once('./foot.php');
?>