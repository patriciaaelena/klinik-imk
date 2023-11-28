<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
$state = [
  [
    'name' => 'Proses',
    'icon' => 'fas fa-info-circle',
    'color' => 'warning',
  ],
  [
    'name' => 'Disetujui',
    'icon' => 'fas fa-check-circle',
    'color' => 'success',
  ],
  [
    'name' => 'Perubahan',
    'icon' => 'fas fa-info-circle',
    'color' => 'secondary',
  ],
  [
    'name' => 'Ditangguhkan',
    'icon' => 'fas fa-info-circle',
    'color' => 'secondary',
  ],
  [
    'name' => 'Tidak Disetujui',
    'icon' => 'fas fa-times-circle',
    'color' => 'danger',
  ],
];
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
        <div class="info-box-content">
          <?php if ($user['role'] == "1") { ?>
            <span class="info-box-text"><?= $user['nama_unitkerja'] ?></span>
            <?php if ($user['nama_induk'] !== NULL) { ?>
              <span class="info-box-text"><?= $user['nama_induk'] ?></span>
            <?php } ?>
          <?php } else { ?>
            <span class="info-box-text">Unit Kerja</span>
            <span class="info-box-number">10 <small>unit</small></span>
          <?php } ?>
        </div>
      </div>
    </div>

    <?php if ($user['role'] == "0") { ?>
      <div class="col">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Template Persetujuan</span>
            <span class="info-box-number">2,000 <small>template</small></span>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="col">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Pegawai</span>
          <span class="info-box-number">2,000 <small>pegawai</small></span>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="card card-info">
        <div class="card-header">Pengajuan Cuti Sebelum Bulan <?= $fmtMY->format(strtotime(date('Y-m-d'))) ?></div>
        <div class="card-body py-0 pt-3">
          <div class="row">
            <?php foreach ($state as $row) { ?>
              <div class="col">
                <div class="info-box">
                  <span class="info-box-icon bg-<?= $row['color'] ?> elevation-1"><i class="<?= $row['icon'] ?>"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text"><?= $row['name'] ?></span>
                    <span class="info-box-number">10 <small>permohonan</small></span>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="card card-info">
        <div class="card-header">Pengajuan Cuti Bulan Ini</div>
        <div class="card-body py-0 pt-3">
          <div class="row">
            <?php foreach ($state as $row) { ?>
              <div class="col">
                <div class="info-box">
                  <span class="info-box-icon bg-<?= $row['color'] ?> elevation-1"><i class="<?= $row['icon'] ?>"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text"><?= $row['name'] ?></span>
                    <span class="info-box-number">10 <small>permohonan</small></span>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>