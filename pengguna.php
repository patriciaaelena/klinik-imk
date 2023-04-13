<?php
$halaman = "Kelola Pengguna";
include "header.php";
if($user['id']!=1){
    header('location: index.php', true, 301);
    exit();
}
if(isset($_POST['tambah'])){
    unset($_POST['tambah']);
    admin('tambah',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if(isset($_POST['ubah'])){
    unset($_POST['ubah']);
    admin('ubah',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
if(isset($_POST['hapus'])){
    unset($_POST['hapus']);
    admin('hapus',$_POST);
    header('Refresh: 0', true, 301);
    exit();
}
$pengguna = admin('','');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Pengguna</h1>
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
                            <h3 class="card-title">Daftar Pengguna</h3>

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
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Nomor HP</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pengguna as $value) { ?>
                                    <tr>
                                        <td><?= $value['username'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                        <td><?= $value['hp_adm'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal-default2"
                                                onclick="dataModalUbah(<?= $value['id_adm'] ?>,'<?= $value['username'] ?>','<?= $value['nama_adm'] ?>','<?= $value['hp_adm'] ?>')"><i
                                                    class="fas fa-edit"></i></button>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="id_adm" value="<?= $value['id_adm'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" name="hapus" onclick="return confirm('Yakin menghapus data ini?')"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
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
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="username-tambah">Username</label>
                        <input type="text" class="form-control" id="username-tambah" name="username" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="nama_adm" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="hp">No HP</label>
                        <input type="text" class="form-control" id="hp" name="hp_adm" required>
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
                <h4 class="modal-title">Ubah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_adm" id="id-adm">
                    <div class="form-group">
                        <label style="font-weight: 400;" for="username-ubah">Username</label>
                        <input type="text" class="form-control" id="username-ubah" disabled>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="nama-ubah">Nama</label>
                        <input type="text" class="form-control" id="nama-ubah" name="nama_adm" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 400;" for="hp-ubah">No HP</label>
                        <input type="text" class="form-control" id="hp-ubah" name="hp_adm" required>
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
const dataModalUbah = (id, username, nama, hp) => {
    $("#id-adm").val(id);
    $("#username-ubah").val(username);
    $("#nama-ubah").val(nama);
    $("#hp-ubah").val(hp);
}
$(document).ready(() => {});
</script>
<?php
include "footer.php";
?>