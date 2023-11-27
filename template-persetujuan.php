<?php
$halaman = 'Template Persetujuan';
require_once('./head.php');
require_once('./function/Jabatan.php');
require_once('./function/TemplatePersetujuan.php');
if ($_SESSION['auth']['role'] != '0') {
  require_once('./401.php');
  die;
}
if (isset($_POST['tambah'])) {
  unset($_POST['tambah']);
  TemplatePersetujuan('CREATE', $_POST);
  header("Refresh:0");
  die;
}
if (isset($_POST['hapus'])) {
  unset($_POST['hapus']);
  TemplatePersetujuan('DELETE', $_POST);
  header("Refresh:0");
  die;
}
$select = Jabatan('ONE', []);
$rows = TemplatePersetujuan('', []);
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
            <h3 class="card-title">Daftar Template Persetujuan</h3>
            <div class="float-sm-right">
              <button class="btn btn-secondary rounded-circle" data-toggle="modal" data-target="#modal-add">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th style="width: 60px">No</th>
                  <th style="width: 120px">Aksi</th>
                  <th>Nama Template</th>
                  <th>Persetujuan 1</th>
                  <th>Persetujuan 2</th>
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
                      <button type="button" class="btn btn-sm btn-danger" onclick="handleDelete('<?= $row['id_tamplate'] ?>');"><i class="fas fa-trash"></i></button>
                    </td>
                    <td><?= $row['nama_tamplate'] ?></td>
                    <td><?= $row['nama_pertama'] ?></td>
                    <td><?= $row['nama_kedua'] ?></td>
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
      $add['nama_tamplate'] = " value='" . $_SESSION['flash']['data']['nama_tamplate'] . "'";
      $add['persetujuan_pertama'] = $_SESSION['flash']['data']['persetujuan_pertama'];
      $add['persetujuan_kedua'] = $_SESSION['flash']['data']['persetujuan_kedua'];
    } else {
      $edit['id_tamplate'] = " value='" . $_SESSION['flash']['data']['id_tamplate'] . "'";
      $edit['nama_tamplate'] = " value='" . $_SESSION['flash']['data']['nama_tamplate'] . "'";
      $edit['persetujuan_pertama'] = $_SESSION['flash']['data']['persetujuan_pertama'];
      $edit['persetujuan_kedua'] = $_SESSION['flash']['data']['persetujuan_kedua'];
    }
  }
  ?>
  <!-- Modal Add -->
  <div class="modal fade" id="modal-add" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Template Persetujuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate method="post" id="form-add">
          <div class="modal-body">
            <div class="col pb-3">
              <label for="nama_tamplate1" class="form-label">Nama Template <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama_tamplate1" name="nama_tamplate" <?= $add['nama_tamplate'] ?? '' ?> required>
              <div class="invalid-feedback">
                Harus diisi
              </div>
            </div>
            <div class="col pb-3">
              <label for="persetujuan_pertama" class="form-label">Persetujuan Pertama <span class="text-danger">*</span></label>
              <select class="form-select custom-select rounded-0 select2bs4" id="persetujuan_pertama1" name="persetujuan_pertama" required>
                <option value="" selected>Pilih Jabatan</option>
                <?php foreach ($select as $row) { ?>
                  <option value="<?= $row['id_jabatan'] ?>" <?= isset($add['persetujuan_pertama']) ? ($row['id_jabatan'] === $add['persetujuan_pertama'] ? " selected" : "") : "" ?>><?= "$row[nama_jabatan] $row[nama_unitkerja]" ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">
                Harus dipilih
              </div>
            </div>
            <div class="col pb-3">
              <label for="persetujuan_kedua" class="form-label">Persetujuan Kedua <span class="text-danger">*</span></label>
              <select class="form-select custom-select rounded-0 select2bs4" id="persetujuan_kedua1" name="persetujuan_kedua" required disabled>
                <option value="" selected>Pilih Jabatan</option>
                <?php foreach ($select as $row) { ?>
                  <option value="<?= $row['id_jabatan'] ?>" <?= isset($add['persetujuan_kedua']) ? ($row['id_jabatan'] === $add['persetujuan_kedua'] ? " selected" : "") : "" ?>><?= "$row[nama_jabatan] $row[nama_unitkerja]" ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">
                Harus dipilih
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
    <input type="hidden" name="id_tamplate" id="delete-id">
    <input type="hidden" name="hapus">
  </form>
</section>
<script>
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
    $('#persetujuan_pertama1').on('change', () => {
      const first = $('#persetujuan_pertama1').val();
      if (Boolean(first)) {
        $('#persetujuan_kedua1 option').each((i, val) => {
          val.removeAttribute('disabled');
          if (val.value == first) val.setAttribute('disabled', true);
        });
        $('#persetujuan_kedua1').select2({
          theme: 'bootstrap4'
        });
        $('#persetujuan_kedua1').prop('disabled', false);
      } else {
        $('#persetujuan_kedua1').prop('disabled', true);
      }
    });
    $('#persetujuan_pertama').on('change', () => {
      const first = $('#persetujuan_pertama').val();
      if (Boolean(first)) {
        $('#persetujuan_kedua option').each((i, val) => {
          val.removeAttribute('disabled');
          if (val.value == first) val.setAttribute('disabled', true);
        });
        $('#persetujuan_kedua').select2({
          theme: 'bootstrap4'
        });
        $('#persetujuan_kedua').prop('disabled', false);
      } else {
        $('#persetujuan_kedua').prop('disabled', true);
      }
    });
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