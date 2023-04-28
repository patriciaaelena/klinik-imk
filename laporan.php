<?php
$halaman = "Laporan";
include "header.php";
if (isset($_POST['tambah'])) {
    unset($_POST['tambah']);
    $_POST['id_adm'] = $user['id'];
    obat('tambah', $_POST);
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
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Pengelolaan</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header no-print">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" style="font-weight: 400;" for="kategori">Kategori</label>
                                </div>
                                <select class="form-control" name="kategori" id="kategori" required>
                                    <option value="obat" selected>Data Obat</option>
                                    <option value="Masuk">Data Obat Masuk</option>
                                    <option value="Keluar">Data Obat Keluar</option>
                                </select>
                            </div>
                            <div class="input-group mb-3" id="form-jenis">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" style="font-weight: 400;" for="jenis">Jenis</label>
                                </div>
                                <select class="form-control" name="jenis" id="jenis" required>
                                    <option value="Semua" selected>Semua</option>
                                    <option value="Obat Bebas">Obat Bebas</option>
                                    <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                                    <option value="Obat Keras">Obat Keras</option>
                                </select>
                            </div>
                            <div class="input-group mb-3 d-none" id="form-per">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" style="font-weight: 400;" for="per">Per - </label>
                                </div>
                                <select class="form-control" name="per" id="per" required>
                                    <option value="1" selected>Hari ini</option>
                                    <option value="2">Kemarin</option>
                                    <option value="3">Bulan Ini</option>
                                    <option value="4">Bulan Kemarin</option>
                                    <option value="5">Tanggal</option>
                                </select>
                            </div>
                            <div class="form-group d-none mb-3" id="form-tanggal">
                                <label style="font-weight: 400;">Tanggal <span id="label"></span></label>
                                <div class="row">
                                    <div class="input-group col-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="font-weight: 400;" for="dari">Dari</label>
                                        </div>
                                        <input class="form-control" type="date" name="dari" id="dari">
                                    </div>
                                    <div class="input-group col-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="font-weight: 400;" for="sampai">Sampai</label>
                                        </div>
                                        <input class="form-control" type="date" name="sampai" id="sampai" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse">
                                <button class="btn btn-primary" id="submit">Filter</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-none no-print" id="form-print">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-sm btn-success" id="start-print">PDF</button>
                                </div>
                            </div>
                            <div class="d-print-block" id="laporan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    const successState = (data, success) => {
        $('#laporan').addClass('m-3');
        $('#laporan').html(data);
        $('#form-print').removeClass('d-none');
    }
    $(document).ready(() => {
        const dateNow = new Date();
        const dateNowStr = `${dateNow.getFullYear()}-${String(dateNow.getMonth()).padStart(2,'0')}-${String(dateNow.getDate()).padStart(2,'0')}`;
        const kategori = $('#kategori');
        const jenis = $('#jenis');
        const per = $('#per');
        const dari = $('#dari');
        const sampai = $('#sampai');
        dari.prop('max', dateNowStr);
        sampai.prop('max', dateNowStr);
        kategori.on('change', ({
            target
        }) => {
            console.log(target.value);
            if (target.value == "obat") {
                $('#form-jenis').removeClass('d-none');
                $('#form-per').addClass('d-none');
                per.val('1').change();
            } else {
                $('#form-jenis').addClass('d-none');
                $('#form-per').removeClass('d-none');
                $('#label').html((target.value))
                per.val('1').change();
            }
        });
        per.on('change', ({
            target
        }) => {
            if (target.value == "5") {
                $('#form-tanggal').removeClass('d-none');
            } else {
                $('#form-tanggal').addClass('d-none');
            }
        });
        dari.on("change", ({
            target
        }) => {
            if (Boolean(target.value)) {
                sampai.prop('disabled', false);
                sampai.prop('min', target.value);
                sampai.val('');
            } else {
                sampai.prop('disabled', true);
                sampai.val('');
            }
            console.log(target.value);
        });
        $('#submit').on('click', () => {
            if (kategori.val() == 'obat') {
                $.post('ajax.php', {
                    kategori: 'obat',
                    jenis: jenis.val(),
                }, successState);
            } else {
                if (per.val() != '5') {
                    console.log('gas');
                    $.post('ajax.php', {
                        kategori: kategori.val(),
                        per: per.val(),
                    }, successState);
                } else {
                    if (Boolean(dari.val()) && Boolean(sampai.val())) {
                        $.post('ajax.php', {
                            kategori: kategori.val(),
                            dari: dari.val(),
                            sampai: sampai.val(),
                        }, successState);
                    } else {
                        alert("Tanggal 'Dari' - 'Sampai' harus diisi!");
                    }
                }
            }
        });
        $('#start-print').on('click',()=>{
            $('.no-print').addClass('d-print-none');
            window.print();
        });
    });
</script>
<?php
include "footer.php";
?>