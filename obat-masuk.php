<?php
$halaman = "Obat Masuk";
include "header.php";
if (isset($_POST['tambah'])) {
    unset($_POST['tambah']);
    $_POST['id_adm'] = $user['id'];
    $_POST['tersedia'] = $_POST['awal'];
    obatMasuk('tambah', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if (isset($_POST['ubah'])) {
    unset($_POST['ubah']);
    obatMasuk('ubah', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if (isset($_POST['hapus'])) {
    unset($_POST['hapus']);
    obatMasuk('hapus', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if (isset($_POST['kedaluwarsa'])) {
    unset($_POST['kedaluwarsa']);
    $_POST['id_adm'] = $user['id'];
    obatKeluar('kedaluwarsa', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
$obat = obat('', '');
$obatMasuk = obatMasuk('', '');
$arrNewObat = [];
foreach ($obat as $val) {
    array_push($arrNewObat, [
        'value' => $val['id_obat'],
        'tag' => "$val[kode_obat] : $val[nama_obat]",
    ]);
}
$arrNewObat = json_encode($arrNewObat);
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Data Obat Masuk</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Obat Masuk</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-default1">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tabel1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Masuk</th>
                                        <th>Nama Obat</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Kedaluwarsa</th>
                                        <th>Jumlah Awal</th>
                                        <th>Jumlah Tersedia</th>
                                        <th>Pengelola</th>
                                        <th>Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($obatMasuk as $value) {
                                        $now = time(); // or your date as well
                                        $kdwrs = strtotime($value['tgl_kdwrs']);
                                        $datediff = round(($kdwrs - $now) / (60 * 60 * 24));
                                    ?>
                                        <tr>
                                            <td><?= $value['kode_obat_masuk'] ?></td>
                                            <td><?= $value['nama_obat'] ?></td>
                                            <td><?= $value['tgl_masuk'] ?></td>
                                            <td class="<?= $datediff <= 0 ? 'bg-danger' : ($datediff <= 30 ? 'bg-warning' : '') ?>"><?= $value['tgl_kdwrs'] ?> <?= $datediff <= 0 ? "(Kedaluwarsa)" : ($datediff <= 30 ? "(Kedaluwarsa dalam $datediff hari)" : "") ?></td>
                                            <td><?= $value['awal'] ?></td>
                                            <td><?= $value['tersedia'] ?></td>
                                            <td><?= $value['nama_adm'] ?></td>
                                            <td>
                                                <?php if ($user['id'] == $value['id_adm'] && $value['awal'] == $value['tersedia']) { ?>
                                                    <form method="post" style="display: inline;">
                                                        <input type="hidden" name="id_obat_masuk" value="<?= $value['id_obat_masuk'] ?>">
                                                        <input type="hidden" name="id_obat" value="<?= $value['id_obat'] ?>">
                                                        <input type="hidden" name="awal" value="<?= $value['awal'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" name="hapus" onclick="return confirm('Yakin menghapus data ini?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                <?php } else { ?>
                                                    <span class="badge badge-secondary">Tidak tersedia</span>
                                                <?php } ?>
                                                <?php if ($value['tersedia'] != '0' && $datediff <= 30) { ?>
                                                    <form method="post" style="display: inline;">
                                                        <input type="hidden" name="id_obat_masuk" value="<?= $value['id_obat_masuk'] ?>">
                                                        <input type="hidden" name="id_obat" value="<?= $value['id_obat'] ?>">
                                                        <input type="hidden" name="jumlah" value="<?= $value['tersedia'] ?>">
                                                        <input type="hidden" name="tgl_keluar" value="<?= date('Y-m-d') ?>">
                                                        <button type="submit" class="btn btn-sm btn-warning" name="kedaluwarsa" onclick="return confirm('Obat ini sudah kedaluwarsa?\nAksi tidak bisa dibatalkan.')" data-toggle="tooltip" data-placement="left" title="Ketuk apabila kedaluwarsa"><i class="fas fa-share"></i></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-default1" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Obat Masuk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="id_obat">Nama Obat</label>
                        <input type="text" class="form-control" name="id_obat" id="id_obat" />
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="tgl_masuk">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" max="<?= date("Y-m-d") ?>" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="tgl_kdwrs">Tanggal Kedaluwarsa</label>
                        <input type="date" class="form-control" id="tgl_kdwrs" name="tgl_kdwrs" min="<?= date("Y-m-d") ?>" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="awal">Jumlah Obat</label>
                        <input type="number" class="form-control" id="awal" name="awal" min="1" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default2" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Data Obat</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_obat" id="id-obat">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="nama-tambah">Nama Obat</label>
                        <input type="text" class="form-control" id="nama-ubah" name="nama_obat" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="jenis-ubah">Jenis</label>
                        <select class="form-control" name="jenis" id="jenis-ubah" required>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                            <option value="Obat Keras">Obat Keras</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="harga-ubah">Harga</label>
                        <input type="number" class="form-control" id="harga-ubah" name="harga" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="ubah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const dataModalUbah = (id, nama, jenis, harga) => {
        $("#id-obat").val(id);
        $("#nama-ubah").val(nama);
        $("#jenis-ubah").val(jenis);
        $("#harga-ubah").val(harga);
    }
    const listObat = JSON.parse('<?= $arrNewObat ?>');
    $(document).ready(() => {
        $('#modal-default1').on('shown.bs.modal', function(e) {
            // do something...
            $('#id_obat[name="id_obat"]').amsifySuggestags({
                type: 'bootstrap',
                suggestions: listObat,
                tagLimit: 1,
                whiteList: true,
            });
        })
    });
</script>
<?php
include "footer.php";
?>