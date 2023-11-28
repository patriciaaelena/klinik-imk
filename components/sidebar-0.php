<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
?>
<li class="nav-item">
  <a href="<?= $halaman === 'Jenis Cuti' ? 'javascript:void(0)' : './jenis-cuti' ?>" class="nav-link <?= $halaman === 'Jenis Cuti' ? 'active' : '' ?>">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      Jenis Cuti
    </p>
  </a>
</li>
<?php if ($_SESSION['auth']['role'] == '0') { ?>
  <li class="nav-item">
    <a href="<?= $halaman === 'Unit Kerja' ? 'javascript:void(0)' : './unit-kerja' ?>" class="nav-link <?= $halaman === 'Unit Kerja' ? 'active' : '' ?>">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        Unit Kerja
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="<?= $halaman === 'Jabatan' ? 'javascript:void(0)' : './jabatan' ?>" class="nav-link <?= $halaman === 'Jabatan' ? 'active' : '' ?>">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        Jabatan
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="<?= $halaman === 'Template Persetujuan' ? 'javascript:void(0)' : './template-persetujuan' ?>" class="nav-link <?= $halaman === 'Template Persetujuan' ? 'active' : '' ?>">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        Template Persetujuan
      </p>
    </a>
  </li>
<?php } ?>
<li class="nav-item">
  <a href="<?= $halaman === 'Pegawai' ? 'javascript:void(0)' : './pegawai' ?>" class="nav-link <?= $halaman === 'Pegawai' ? 'active' : '' ?>">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      Pegawai
    </p>
  </a>
</li>
<li class="nav-item">
  <a href="<?= $halaman === 'Riwayat Pengajuan Cuti' ? 'javascript:void(0)' : './riwayat-pengajuan-cuti' ?>" class="nav-link <?= $halaman === 'Riwayat Pengajuan Cuti' ? 'active' : '' ?>">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      Riwayat Pengajuan Cuti
    </p>
  </a>
</li>