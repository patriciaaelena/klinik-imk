<?php
include 'function.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['component'])) {
        switch ($_POST['component']) {
            case 'obat1':
                $data = component('masuk', $_POST['id']);
?>
                <div class="form-group">
                    <label style="font-weight: 400;" for="id_obat_masuk">ID Masuk</label>
                    <select class="form-control" name="id_obat_masuk" id="id_obat_masuk" placeholder="Pilih ID Masuk" required>
                        <option value="" selected disabled>-- Pilih ID Masuk --</option>
                        <?php foreach ($data as $row) { ?>
                            <option value="<?= $row['id_obat_masuk'] ?>"><?= $_POST['id'] ?>-<?= $row['id_obat_masuk'] ?>: Stok <?= $row['tersedia'] ?><?= (int)$row['selisih'] < 30 ? ", Kedaluwarsa dalam $row[selisih] hari" : "" ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-weight: 400;" for="jumlah">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value='0' min="1" disabled required>
                </div>
                <script>
                    data = JSON.parse('<?= json_encode($data) ?>');
                    $("#id_obat_masuk").change(() => {
                        const limit = data.find((val) => val.id_obat_masuk === $("#id_obat_masuk").val());
                        $("#jumlah").attr({
                            max: limit.tersedia,
                            disabled: false,
                        })
                    });
                </script>
            <?php
                break;
            case 'obat2':
                $data = component('stok', $_POST['id']);
                break;
        }
    } else if (isset($_POST['kategori'])) {
        switch ($_POST['kategori']) {
            case 'obat':
                $laporan = laporan($_POST['kategori'], $_POST['jenis']);
                $laporan = $laporan["data"];
            ?>
                <center class="mb-4">
                    <h5><b>Laporan Data <?= $_POST['jenis'] == 'Semua' ? "Obat" : $_POST['jenis'] ?></b></h5>
                </center>
                <div class="mb-3" style="text-align: right;">Per tanggal <span id="tgl-ini"></span> WIB</div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Pengelola</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporan as $value) { ?>
                            <tr>
                                <td><?= $value['nama_obat'] ?></td>
                                <td><?= $value['jenis'] ?></td>
                                <td><?= $value['stok'] ?></td>
                                <td><?= $value['harga'] ?></td>
                                <td><?= $value['nama_adm'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align: right;">Jumlah:</th>
                            <th><?= count($laporan) ?> Obat</th>
                        </tr>
                    </tfoot>
                </table>
                <?php
                break;
            default:
                $kategori = $_POST['kategori'];
                if (isset($_POST['per'])) {
                    $laporan = laporan($kategori, $_POST['per']);
                ?>
                    <center class="mb-4">
                        <h5><b>Laporan Data Obat <?= $kategori ?></b></h5>
                    </center>
                    <?php if ($_POST['per'] == "1") { ?>
                        <div class="mb-2" style="text-align: left;">Hari ini, <span id="tgl-filter"><?= date("Y-m-d") ?></span></div>
                    <?php } elseif ($_POST['per'] == "2") { ?>
                        <div class="mb-2" style="text-align: left;">Kemarin, <span id="tgl-filter"><?= date("Y-m-d", strtotime($laporan['dari'])) ?></span></div>
                    <?php } elseif ($_POST['per'] == "3") { ?>
                        <div class="mb-2" style="text-align: left;">Bulan ini, <span id="tgl-filter"><?= date("Y-m-d") ?></span></div>
                    <?php } else { ?>
                        <div class="mb-2" style="text-align: left;">Bulan kemarin, <span id="tgl-filter"><?= date("Y-m-d", strtotime($laporan['dari'])) ?></span></div>
                    <?php } ?>
                    <div class="mb-3" style="text-align: right;">Difilter pada <span id="tgl-ini"></span> WIB</div>
                    <?php
                    if ($kategori == "Masuk") {
                    ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>ID Masuk</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Jumlah Awal</th>
                                    <th>Jumlah Tersedia</th>
                                    <th>Pengelola</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laporan['data'] as $value) {
                                    $now = time(); // or your date as well
                                    $kdwrs = strtotime($value['tgl_kdwrs']);
                                    $datediff = round(($kdwrs - $now) / (60 * 60 * 24));
                                ?>
                                    <tr>
                                        <td><?= $value['nama_obat'] ?></td>
                                        <td><?= $value['id_obat'] ?>-<?= $value['id_obat_masuk'] ?></td>
                                        <td><?= $value['tgl_masuk'] ?></td>
                                        <td class="<?= $datediff <= 0 ? 'bg-danger' : ($datediff <= 30 ? 'bg-warning' : '') ?>"><?= $value['tgl_kdwrs'] ?> <?= $datediff <= 0 ? "(Kedaluwarsa)" : ($datediff <= 30 ? "(Kedaluwarsa dalam $datediff hari)" : "") ?></td>
                                        <td><?= $value['awal'] ?></td>
                                        <td><?= $value['tersedia'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                    ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>ID Keluar</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Pengelola</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laporan['data'] as $value) { ?>
                                    <tr>
                                        <td><?= $value['nama_obat'] ?></td>
                                        <td><?= $value['id_obat'] ?>-<?= $value['id_obat_masuk'] ?>-<?= $value['id_obat_keluar'] ?></td>
                                        <td><?= $value['tgl_keluar'] ?></td>
                                        <td><?= $value['jumlah'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                        <td>
                                            <?php if ($value['kedaluwarsa'] == '0') { ?>
                                                <span class="badge badge-primary">Obat Keluar</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Obat Kedaluwarsa</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                    }
                } else {
                    $laporan = laporan($kategori, ['dari' => $_POST['dari'], 'sampai' => $_POST['sampai']]);
                    ?>
                    <center class="mb-4">
                        <h5><b>Laporan Data Obat <?= $kategori ?></b></h5>
                    </center>
                    <div class="row">
                        <div class="col-3">Dari tanggal</div>
                        <div class="col-1">:</div>
                        <div class="col-8" id="tgl-dari"><?= $_POST['dari']?></div>
                    </div>
                    <div class="row">
                        <div class="col-3">Sampai tanggal</div>
                        <div class="col-1">:</div>
                        <div class="col-8" id="tgl-sampai"><?= $_POST['sampai']?></div>
                    </div>
                    <div class="mb-3" style="text-align: right;">Difilter pada <span id="tgl-ini"></span> WIB</div>
                    <?php
                    if ($kategori == "Masuk") {
                    ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>ID Masuk</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Jumlah Awal</th>
                                    <th>Jumlah Tersedia</th>
                                    <th>Pengelola</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laporan['data'] as $value) {
                                    $now = time(); // or your date as well
                                    $kdwrs = strtotime($value['tgl_kdwrs']);
                                    $datediff = round(($kdwrs - $now) / (60 * 60 * 24));
                                ?>
                                    <tr>
                                        <td><?= $value['nama_obat'] ?></td>
                                        <td><?= $value['id_obat'] ?>-<?= $value['id_obat_masuk'] ?></td>
                                        <td><?= $value['tgl_masuk'] ?></td>
                                        <td class="<?= $datediff <= 0 ? 'bg-danger' : ($datediff <= 30 ? 'bg-warning' : '') ?>"><?= $value['tgl_kdwrs'] ?> <?= $datediff <= 0 ? "(Kedaluwarsa)" : ($datediff <= 30 ? "(Kedaluwarsa dalam $datediff hari)" : "") ?></td>
                                        <td><?= $value['awal'] ?></td>
                                        <td><?= $value['tersedia'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                    ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>ID Keluar</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Pengelola</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laporan['data'] as $value) { ?>
                                    <tr>
                                        <td><?= $value['nama_obat'] ?></td>
                                        <td><?= $value['id_obat'] ?>-<?= $value['id_obat_masuk'] ?>-<?= $value['id_obat_keluar'] ?></td>
                                        <td><?= $value['tgl_keluar'] ?></td>
                                        <td><?= $value['jumlah'] ?></td>
                                        <td><?= $value['nama_adm'] ?></td>
                                        <td>
                                            <?php if ($value['kedaluwarsa'] == '0') { ?>
                                                <span class="badge badge-primary">Obat Keluar</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Obat Kedaluwarsa</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php
                    }
                }
                break;
        }
        ?>
        <script>
            <?php if ($_POST['kategori'] != "obat" && isset($_POST['per'])) { ?>
                isMonth = [3, 4].some((val) => val === <?= $_POST['per'] ?>);
                tglFilter = $("#tgl-filter").html().split("-");
                tglFilter = moments2.set({
                    'year': parseInt(tglFilter[0]),
                    'month': parseInt(tglFilter[1]) - 1,
                    'date': parseInt(tglFilter[2])
                });
                if (isMonth) $("#tgl-filter").html(moments2.format("LL").split(" ").filter((_, idx) => idx !== 0).join(" "));
                else $("#tgl-filter").html(moments2.format("LL"));
            <?php } else if(isset($_POST['dari'])) { ?>
                tglFilter = $("#tgl-dari").html().split("-");
                tglFilter = moments2.set({
                    'year': parseInt(tglFilter[0]),
                    'month': parseInt(tglFilter[1]) - 1,
                    'date': parseInt(tglFilter[2])
                });
                $("#tgl-dari").html(moments2.format("LL"));
                tglFilter = $("#tgl-sampai").html().split("-");
                tglFilter = moments2.set({
                    'year': parseInt(tglFilter[0]),
                    'month': parseInt(tglFilter[1]) - 1,
                    'date': parseInt(tglFilter[2])
                });
                $("#tgl-sampai").html(moments2.format("LL"));
            <?php } ?>
            $("#tgl-ini").html(moments.format("LLL"));
        </script>
<?php
    }
}
