<?php
$halaman = 'Jabatan';
require_once('./head.php');
require_once('./function/UnitKerja.php');
require_once('./function/Jabatan.php');
require_once('./function/TemplatePersetujuan.php');
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
if (isset($_POST['tambah'])) {
  unset($_POST['tambah']);
  Jabatan('CREATE', $_POST);
  header("Refresh:0");
  die;
}
if (isset($_POST['ubah'])) {
  unset($_POST['ubah']);
  Jabatan('UPDATE', $_POST);
  header("Refresh:0");
  die;
}
if (isset($_POST['hapus'])) {
  unset($_POST['hapus']);
  Jabatan('DELETE', $_POST);
  header("Refresh:0");
  die;
}
$select1 = UnitKerja('', []);
$select2 = TemplatePersetujuan('', []);
$rows = Jabatan('', []);
// $select1 = [];
// $select2 = [];
// $rows = [];
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
            <h3 class="card-title">Daftar Jabatan</h3>
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
                  <th>Nama Jabatan</th>
                  <th>Unit Kerja</th>
                  <th>Template Persetujuan</th>
                  <th>Jumlah Pejabat</th>
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
                      <button type="button" class="btn btn-sm btn-primary" onclick="handleEdit('<?= $row['id_jabatan'] ?>','<?= $row['nama_jabatan'] ?>','<?= $row['id_unitkerja'] ?>','<?= $row['id_tamplate'] ?>','<?= $row['hanya_satu'] ?>'==='1');"><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="handleDelete('<?= $row['id_jabatan'] ?>');"><i class="fas fa-trash"></i></button>
                    </td>
                    <td><?= $row['nama_jabatan'] ?></td>
                    <td><?= $row['nama_unitkerja'] ?></td>
                    <td <?= $row['id_tamplate'] ? "data-toggle='tooltip' data-placement='bottom' title='* $row[nama_pertama]<br>** $row[nama_kedua]'" : "" ?>><?= $row['id_tamplate'] ? $row['nama_tamplate'] : "-" ?></td>
                    <td><?= $row['hanya_satu'] === '1' ? "Satu" : "Banyak" ?></td>
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
      $add['id_unitkerja'] = $_SESSION['flash']['data']['id_unitkerja'];
    } else {
      $edit['id_jabatan'] = " value='" . $_SESSION['flash']['data']['id_jabatan'] . "'";
      $edit['nama_jabatan'] = " value='" . $_SESSION['flash']['data']['nama_jabatan'] . "'";
      $edit['id_unitkerja'] = $_SESSION['flash']['data']['id_unitkerja'];
    }
  }
  ?>
  <!-- Modal Add -->
  <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jabatan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <div class="modal-body">
            <div class="col pb-3">
              <label for="nama_jabatan1" class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama_jabatan1" name="nama_jabatan" <?= $add['nama_jabatan'] ?? '' ?> required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-3">
              <label for="id_unitkerja1" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
              <select class="form-select custom-select rounded-0" id="id_unitkerja1" name="id_unitkerja" required>
                <option value="" selected disabled>Pilih Unit Kerja</option>
                <?php foreach ($select1 as $row) { ?>
                  <option value="<?= $row['id_unitkerja'] ?>" <?= isset($add['id_unitkerja']) ? ($row['id_unitkerja'] === $add['id_unitkerja'] ? " selected" : "") : "" ?>><?= $row['nama_unitkerja'] ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">
                Harus dipilih
              </div>
            </div>
            <div class="col pb-3">
              <label for="id_tamplate1" class="form-label">Template Persetujuan</label>
              <select class="form-select custom-select rounded-0 select2bs4" id="id_tamplate1" name="id_tamplate">
                <option value="" selected>Pilih Template Persetujuan</option>
                <?php foreach ($select2 as $row) { ?>
                  <option class="id-<?= $row['persetujuan_pertama'] ?> id-<?= $row['persetujuan_kedua'] ?>" value="<?= $row['id_tamplate'] ?>" <?= isset($add['id_tamplate']) ? ($row['id_tamplate'] === $add['id_tamplate'] ? " selected" : "") : "" ?> title="Pertama : <?= $row['nama_pertama'] ?>&#013;Kedua : <?= $row['nama_kedua'] ?>"><?= $row['nama_tamplate'] ?></option>
                <?php } ?>
              </select>
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
  <!-- Modal Ubah -->
  <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Jabatan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post">
          <input type="hidden" id="id_jabatan" <?= $edit['id_jabatan'] ?? '' ?> name="id_jabatan">
          <div class="modal-body">
            <div class="col pb-3">
              <label for="nama_jabatan" class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required <?= $edit['nama_jabatan'] ?? '' ?>>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-3">
              <label for="id_unitkerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
              <input type="hidden" <?= $edit['id_unitkerja'] ?? '' ?> id="id_unitkerja" name="id_unitkerja">
              <select class="custom-select form-select rounded-0" id="id_unitkerja_select" name="id_unitkerja" disabled>
                <option value="">Pilih Unit Kerja</option>
                <?php foreach ($select1 as $row) { ?>
                  <option value="<?= $row['id_unitkerja'] ?>" <?= isset($edit['id_unitkerja']) ? ($row['id_unitkerja'] === $edit['id_unitkerja'] ? " selected" : "") : "" ?>><?= $row['nama_unitkerja'] ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">
                Harus dipilih
              </div>
            </div>
            <div class="col pb-3">
              <label for="id_tamplate" class="form-label">Template Persetujuan</label>
              <select class="form-select custom-select rounded-0 select2bs4" id="id_tamplate" name="id_tamplate">
                <option value="">Pilih Template Persetujuan</option>
                <?php foreach ($select2 as $row) { ?>
                  <option class="id-<?= $row['persetujuan_pertama'] ?> id-<?= $row['persetujuan_kedua'] ?>" value="<?= $row['id_tamplate'] ?>" <?= isset($edit['id_tamplate']) ? ($row['id_tamplate'] === $edit['id_tamplate'] ? " selected" : "") : "" ?> title="Pertama : <?= $row['nama_pertama'] ?>&#013;Kedua : <?= $row['nama_kedua'] ?>"><?= $row['nama_tamplate'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col pb-3">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="hanya_satu" class="custom-control-input" id="hanya_satu">
                <label class="custom-control-label" for="hanya_satu">Hanya satu pejabat?</label>
              </div>
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
    <input type="hidden" name="id_jabatan" id="delete-id">
    <input type="hidden" name="hapus">
  </form>
</section>
<script>
  const handleEdit = (id, name, unit, template, checked) => {
    $('#id_jabatan').val(id);
    $('#nama_jabatan').val(name);
    $('#id_unitkerja').val(unit);
    $('#id_unitkerja_select').val(unit);
    $('#id_tamplate').val(template);
    $('#hanya_satu').prop('checked', checked);
    $('#id_tamplate option').each((i, val) => {
      val.removeAttribute('disabled');
      if (val.classList.contains(`id-${id}`)) val.setAttribute('disabled', true);
    });
    $('#id_tamplate').select2({
      theme: 'bootstrap4'
    });
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

  $(document).ready(() => {
    <?php if (isset($_SESSION['flash']['type'])) { ?>
      <?php if ($_SESSION['flash']['type'] === 'ADD') { ?>
        $('#modal-add').modal('show');
      <?php } else { ?>
        $('#modal-edit').modal('show');
      <?php } ?>
    <?php } ?>
  });
</script>
<?php
require_once('./foot.php');
?>