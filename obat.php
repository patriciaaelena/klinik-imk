<?php
$halaman = "Data Obat";
include "header.php";
if(isset($_POST['tambah'])){
    unset($_POST['tambah']);
    $_POST['id_adm'] = $user['id'];
    obat('tambah',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if(isset($_POST['ubah'])){
    unset($_POST['ubah']);
    obat('ubah',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if(isset($_POST['hapus'])){
    unset($_POST['hapus']);
    obat('hapus',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
$obat = obat('','');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Data Obat</h1>
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
                            <h3 class="card-title">Daftar Obat</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-toggle="modal"
                                    data-target="#modal-default1">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tabel1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jenis</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Pengelola</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($obat as $value) { ?>
                                    <tr>
                                        <td><?= $value['nama_obat'] ?></td>
                                        <td><?= $value['jenis'] ?></td>
                                        <td><?= $value['stok'] ?></td>
                                        <td><?= $value['harga'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                        <td>
                                            <?php if($user['id']==$value['id_adm']) { ?>
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-default2"
                                                onclick="dataModalUbah(<?= $value['id_obat'] ?>,'<?= $value['nama_obat'] ?>','<?= $value['jenis'] ?>','<?= $value['harga'] ?>')"><i
                                                    class="fas fa-edit"></i></button>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="id_obat" value="<?= $value['id_obat'] ?>">
                                                <button type="submit" class="btn btn-danger" name="hapus" onclick="return confirm('Yakin menghapus data ini?')"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                            <?php } else { ?>
                                            <span class="badge badge-secondary">Tidak tersedia</span>
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
                <h4 class="modal-title">Tambah Data Obat</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="nama-tambah">Nama Obat</label>
                        <input type="text" class="form-control" id="nama-tambah" name="nama_obat" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="jenis">Jenis</label>
                        <select class="form-control" name="jenis" id="jenis" required>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                            <option value="Obat Keras">Obat Keras</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
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
$(document).ready(() => {});
</script>
<?php
include "footer.php";
?>