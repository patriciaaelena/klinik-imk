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
        $arrData = [];
        switch ($_POST['kategori']) {
            case 'obat':
                $query = "SELECT o.*,a.nama_adm FROM obat o JOIN admin a USING(id_adm)";
                $query .= $_POST['jenis'] == 'Semua' ? "" : " WHERE jenis='$_POST[jenis]'";
                $res = $conn->query($query);
                foreach ($res as $row) {
                    array_push($arrData, $row);
                }
            ?>
                <center class="mb-3"><h5><b>Laporan Data <?= $_POST['jenis'] == 'Semua' ? "Obat" : $_POST['jenis'] ?></b></h5></center>
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
                        <?php foreach ($arrData as $value) { ?>
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
                            <th><?= count($arrData) ?> Obat</th>
                        </tr>
                    </tfoot>
                </table>
<?php
                break;

            default:
                $kategori = $_POST['kategori'];
                if (isset($_POST['per'])) {
                } else {
                }
                break;
        }
    }
}
