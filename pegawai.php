<?php
$halaman = 'Pegawai';
require_once('./head.php');
require_once('./function/Jabatan.php');
require_once('./function/Pegawai.php');
if ($_SESSION['auth']['role'] == '2') {
  require_once('./401.php');
  die;
}
if (isset($_POST['tambah'])) {
  unset($_POST['tambah']);
  Pegawai('CREATE', $_POST);
  header("Refresh:0");
  die;
}
$cond = [];
if ($user['role'] == "1") {
  $cond[] = $user['id_unitkerja'];
  if ($user['id_induk'] !== NULL) $cond[] = $user['id_induk'];
  $cond = array_map(function (int $value): string {
    return "id_unitkerja='$value'";
  }, $cond);
  $cond = ["where" => "WHERE " . implode(" OR ", $cond)];
}
$select = Jabatan('GET-FREE', []);
$rows = Pegawai('', count($cond) === 0 ? [] : $cond);
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
            <h3 class="card-title">Daftar Pegawai</h3>
            <div class="float-sm-right">
              <button class="btn btn-secondary rounded-circle" data-toggle="modal" data-target="#modal-add">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-3 table-responsive">
            <table id="main-table" class="table table-bordered table-striped" style="width:100%">
              <thead>
                <tr>
                  <th style="width: 25px">No</th>
                  <th style="width: 40px">Aksi</th>
                  <th style="width: 120px">NIK</th>
                  <th style="width: 120px">NIP</th>
                  <th style="width: 200px">Unit Kerja</th>
                  <th style="width: 260px">Jabatan</th>
                  <th>Nama Pegawai</th>
                  <th style="width: 50px">Status</th>
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
                      <a class="btn btn-sm btn-primary" href="./detail-pegawai?id=<?= $row['id_pegawai'] ?>"><i class="fas fa-eye"></i></a>
                    </td>
                    <td><?= $row['nik'] ?></td>
                    <td><?= $row['nip'] ?></td>
                    <td><?= $row['nama_unitkerja'] ?></td>
                    <td><?= $row['nama_jabatan'] ?></td>
                    <td><?= $row['nama_pegawai'] ?></td>
                    <td><?= $row['status'] ?></td>
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
  <?php
  $add = [];
  $edit = [];
  if (isset($_SESSION['flash']['type'])) {
    if ($_SESSION['flash']['type'] === 'ADD') {
      $add['nik'] = " value='" . $_SESSION['flash']['data']['nik'] . "'";
      $add['nip'] = " value='" . $_SESSION['flash']['data']['nip'] . "'";
      $add['mulai_kerja'] = " value='" . $_SESSION['flash']['data']['mulai_kerja'] . "'";
      $add['nama_pegawai'] = " value='" . $_SESSION['flash']['data']['nama_pegawai'] . "'";
      $add['id_jabatan'] = $_SESSION['flash']['data']['id_jabatan'];
      $add['status'] = $_SESSION['flash']['data']['status'];
    }
  }
  ?>
  <!-- Modal Add -->
  <div class="modal fade" id="modal-add" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <div class="modal-body">
            <div class="row px-3">
              <div class="col-6 pb-2">
                <label for="nik1" class="form-label">NIK <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nik1" name="nik" <?= $add['nik'] ?? '' ?> required>
                <div class="invalid-feedback">
                  Harus diisi
                </div>
              </div>
              <div class="col-6 pb-3">
                <label for="nip1" class="form-label">NIP</label>
                <input type="number" class="form-control" id="nip1" name="nip" <?= $add['nip'] ?? '' ?>>
              </div>
              <div class="col-12 pb-3">
                <label for="nama_pegawai1" class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_pegawai1" name="nama_pegawai" <?= $add['nama_pegawai'] ?? '' ?> required>
                <div class="invalid-feedback">
                  Harus diisi
                </div>
              </div>
              <div class="col-12 pb-3">
                <label for="id_jabatan1" class="form-label">Jabatan <span class="text-danger">*</span></label>
                <select class="form-select custom-select rounded-0 select2bs4" id="id_jabatan1" name="id_jabatan" <?= $add['id_jabatan'] ?? '' ?> required style="width: 100%;">
                  <option value="">Pilih Jabatan</option>
                  <?php foreach ($select as $row) { ?>
                    <option value="<?= $row['id_jabatan'] ?>" <?= isset($add['id_jabatan']) ? ($add['id_jabatan'] === $row['id_jabatan'] ? " selected" : "") : "" ?>><?= "$row[nama_jabatan] $row[nama_unitkerja]" ?></option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">
                  Harus dipilih
                </div>
              </div>
              <div class="col-12 pb-3">
                <label for="mulai_kerja1" class="form-label">Mulai Kerja <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="mulai_kerja1" name="mulai_kerja" <?= $add['mulai_kerja'] ?? '' ?> max="<?= date("Y-m-d") ?>" onkeydown="return false;" required>
                <div class="invalid-feedback">
                  Harus diisi
                </div>
              </div>
              <div class="col-12 pb-3">
                <label for="status1" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select custom-select rounded-0" id="status1" name="status" <?= $add['status'] ?? '' ?> required>
                  <option value="TEKON" <?= isset($add['status']) ? ($add['status'] === "TEKON" ? " selected" : "") : "" ?>>TEKON</option>
                  <option value="PNS" <?= isset($add['status']) ? ($add['status'] === "PNS" ? " selected" : "") : "" ?>>PNS</option>
                </select>
                <div class="invalid-feedback">
                  Harus dipilih
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
          </div>
        </form>
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