<?php
$halaman = "Obat Keluar";
include "header.php";
if (isset($_POST['tambah'])) {
    unset($_POST['tambah']);
    $_POST['id_adm'] = $user['id'];
    obatKeluar('tambah', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if (isset($_POST['ubah'])) {
    unset($_POST['ubah']);
    obat('ubah', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if (isset($_POST['hapus'])) {
    unset($_POST['hapus']);
    obat('hapus', $_POST);
    header('Refresh: 0', true, 301);
    exit();
}
$obat = obat('', '');
$obatKeluar = obatKeluar('', '');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Data Obat Keluar</h1>
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
                            <h3 class="card-title">Daftar Obat Keluar</h3>

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
                                        <th>Kode Keluar</th>
                                        <th>Nama Obat</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Jumlah Keluar</th>
                                        <th>Pengelola</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($obatKeluar as $value) { ?>
                                        <tr>
                                            <td><?= $value['kode_obat_keluar'] ?></td>
                                            <td><?= $value['nama_obat'] ?></td>
                                            <td><?= $value['tgl_keluar'] ?></td>
                                            <td><?= $value['jumlah'] ?></td>
                                            <td><?= $value['nama_adm'] ?></td>
                                            <td>
                                                <?php if ($value['kedaluwarsa'] == '0'){ ?>
                                                    <span class="badge badge-primary">Obat Keluar</span>
                                                <?php } else{ ?> 
                                                    <span class="badge badge-danger">Obat Kedaluwarsa</span>
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
                <h4 class="modal-title">Tambah Data Obat Keluar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="id_obat">Nama Obat</label>
                        <select class="form-control" name="id_obat" id="id_obat" required>
                            <option value="" selected disabled>-- Pilih Obat --</option>
                            <?php
                            foreach ($obat as $val) {
                                if ((int)$val['stok'] != 0) {
                            ?>
                                    <option value="<?= $val['id_obat'] ?>"><?= $val['kode_obat'] ?> : <?= $val['nama_obat'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div id="obat1">
                        <div class="form-group">
                            <label style="font-weight: 400;" for="id_obat_masuk">Kode Masuk</label>
                            <select class="form-control" name="id_obat_masuk" id="id_obat_masuk" placeholder="Pilih Kode Masuk" required>
                                <option value="" selected disabled>-- Pilih Kode Masuk --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 400;" for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value='0' min="1" disabled required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="tgl_keluar">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar" max="<?= date("Y-m-d") ?>" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="tambah" onclick="return confirm('Apakah data sudah benar?\nAksi tidak dapat dibatalkan.')">Simpan</button>
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
    $(document).ready(() => {
        $("#id_obat").change(() => {
            $.post('ajax.php', {
                component: 'obat1',
                id: $("#id_obat").val(),
            }, (data, status) => {
                $("#obat1").html(data);
            });
        });
    });
</script>
<?php
include "footer.php";
?>