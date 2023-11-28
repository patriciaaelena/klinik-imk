<?php
$halaman = 'Unit Kerja';
require_once('./head.php');
require_once('./function/UnitKerja.php');
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
if (isset($_POST['tambah'])) {
  unset($_POST['tambah']);
  UnitKerja('CREATE', $_POST);
  header("Refresh:0");
  die;
}
if (isset($_POST['ubah'])) {
  unset($_POST['ubah']);
  UnitKerja('UPDATE', $_POST);
  header("Refresh:0");
  die;
}
if (isset($_POST['hapus'])) {
  unset($_POST['hapus']);
  UnitKerja('DELETE', $_POST);
  header("Refresh:0");
  die;
}
$rows = UnitKerja('', []);
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
            <h3 class="card-title">Daftar Unit Kerja</h3>
            <div class="float-sm-right">
              <button class="btn btn-secondary rounded-circle" data-toggle="modal" data-target="#modal-add">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table  table-striped">
              <thead>
                <tr>
                  <th style="width: 60px">No</th>
                  <th style="width: 120px">Aksi</th>
                  <th>Nama Unit Kerja</th>
                  <th>Induk</th>
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
                      <button type="button" class="btn btn-sm btn-primary" onclick="handleEdit('<?= $row['id_unitkerja'] ?>','<?= $row['nama_unitkerja'] ?>','<?= $row['id_induk'] ?? '' ?>');"><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="handleDelete('<?= $row['id_unitkerja'] ?>');"><i class="fas fa-trash"></i></button>
                    </td>
                    <td><?= $row['nama_unitkerja'] ?></td>
                    <td><?= $row['induk'] ?? '-' ?></td>
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
      $add['nama_unitkerja'] = " value='" . $_SESSION['flash']['data']['nama_unitkerja'] . "'";
      $add['id_induk'] = $_SESSION['flash']['data']['id_induk'] ?? '';
    } else {
      $edit['id_unitkerja'] = " value='" . $_SESSION['flash']['data']['id_unitkerja'] . "'";
      $edit['nama_unitkerja'] = " value='" . $_SESSION['flash']['data']['nama_unitkerja'] . "'";
      $edit['id_induk'] = $_SESSION['flash']['data']['id_induk'] ?? '';
    }
  }
  ?>
  <!-- Modal Add -->
  <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Unit Kerja</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <div class="modal-body">
            <div class="col pb-2">
              <label for="nama_unitkerja1" class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama_unitkerja1" name="nama_unitkerja" <?= $add['nama_unitkerja'] ?? '' ?> required>
              <div class="invalid-feedback">
                Masukkan Nama
              </div>
            </div>
            <div class="col pb-2">
              <label for="id_induk1" class="form-label">Induk</label>
              <select class="custom-select form-select rounded-0" id="id_induk1" name="id_induk" <?= $add['id_induk'] ?? '' ?>>
                <option value="">Pilih Induk</option>
                <?php foreach ($rows as $row) { ?>
                  <option value="<?= $row['id_unitkerja'] ?>" <?= isset($add['id_induk']) ? ($row['id_unitkerja'] === $add['id_induk'] ? " selected" : "") : "" ?>><?= $row['nama_unitkerja'] ?></option>
                <?php } ?>
              </select>
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
  <!-- Modal Ubah -->
  <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Unit Kerja</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <input type="hidden" id="id_unitkerja" <?= $edit['id_unitkerja'] ?? '' ?> name="id_unitkerja">
          <div class="modal-body">
            <div class="col pb-2">
              <label for="nama_unitkerja" class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama_unitkerja" name="nama_unitkerja" required <?= $edit['nama_unitkerja'] ?? '' ?>>
              <div class="invalid-feedback">
                Masukkan Nama
              </div>
            </div>
            <div class="col pb-2">
              <label for="id_induk" class="form-label">Induk</label>
              <select class="custom-select form-select rounded-0" id="id_induk" name="id_induk" <?= $edit['id_induk'] ?? '' ?>>
                <option value="">Pilih Induk</option>
                <?php foreach ($rows as $row) { ?>
                  <option value="<?= $row['id_unitkerja'] ?>" <?= isset($edit['id_induk']) ? ($row['id_unitkerja'] === $edit['id_induk'] ? " selected" : "") : "" ?>><?= $row['nama_unitkerja'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="ubah">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <form method="post" class="d-inline" id="form-delete">
    <input type="hidden" name="id_unitkerja" id="delete-id">
    <input type="hidden" name="hapus">
  </form>
</section>
<script>
  const handleEdit = (id, name, parent) => {
    $('#id_induk option').each((i, val) => {
      if (val.value == id) val.classList.add("d-none");
    })
    $('#id_unitkerja').val(id);
    $('#nama_unitkerja').val(name);
    $('#id_induk').val(parent);
    $('#modal-edit').modal('show');
  }
  const handleDelete = (id) => {
    $('#delete-id').val(id);
    Swal.fire({
      title: "Yakin menghapus data?",
      showCancelButton: true,
      confirmButtonText: "Ya",
      canselButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form-delete').submit();
      }
    });
  }

  $('#modal-edit').on('hidden.bs.modal', (event) => {
    $('#id_induk option').each((name, val) => {
      val.classList.remove("d-none")
    })
  })

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