<?php
$halaman = 'Detail Pegawai';
require_once('./head.php');
require_once('./function/Jabatan.php');
require_once('./function/Pegawai.php');
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ./pegawai");
  die;
}
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
if (isset($_POST['tambah'])) {
  unset($_POST['tambah']);
  Pegawai('CREATE', $_POST);
  header("Refresh:0");
  die;
}
$select = Jabatan('GET-FREE', []);
$rows = Pegawai('', []);
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
          <div class="card-body p-3">
            <table id="main-table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 30px">No</th>
                  <th style="width: 40px">Aksi</th>
                  <th style="width: 120px">NIK</th>
                  <th style="width: 120px">NIP</th>
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
                      <a class="btn btn-sm btn-primary" href=""><i class="fas fa-eye"></i></a>
                    </td>
                    <td><?= $row['nik'] ?></td>
                    <td><?= $row['nip'] ?></td>
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
      $add['nama_jabatan'] = " value='" . $_SESSION['flash']['data']['nama_jabatan'] . "'";
      $add['id_unitkerja'] = " value='" . $_SESSION['flash']['data']['id_unitkerja'] . "'";
    } else {
      $edit['id_pegawai'] = " value='" . $_SESSION['flash']['data']['id_pegawai'] . "'";
      $edit['nama_jabatan'] = " value='" . $_SESSION['flash']['data']['nama_jabatan'] . "'";
      $edit['id_unitkerja'] = " value='" . $_SESSION['flash']['data']['id_unitkerja'] . "'";
    }
  }
  ?>
  <!-- Modal Add -->
  <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <div class="modal-body">
            <div class="col pb-3">
              <label for="nama_jabatan1" class="form-label">Nama Pegawai</label>
              <input type="text" class="form-control" id="nama_jabatan1" name="nama_jabatan" <?= $add['nama_jabatan'] ?? '' ?> required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-3">
              <label for="id_unitkerja" class="form-label">Unit Kerja</label>
              <select class="form-select form-select custom-select rounded-0" id="id_unitkerja1" name="id_unitkerja" <?= $add['id_unitkerja'] ?? '' ?> required>
                <option value="" selected disabled>Pilih Unit Kerja</option>
                <?php foreach ($select as $row) { ?>
                  <option value="<?= $row['id_unitkerja'] ?>"><?= $row['nama_unitkerja'] ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">
                Harus dipilih
              </div>
            </div>
            <div class="col pb-3">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="hanya_satu" class="custom-control-input" id="hanya_satu1">
                <label class="custom-control-label" for="hanya_satu1">Hanya satu pejabat?</label>
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
  <form method="post" class="d-inline" id="form-delete">
    <input type="hidden" name="id_jabatan" id="delete-id">
    <input type="hidden" name="hapus">
  </form>
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