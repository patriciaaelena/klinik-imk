<?php
include 'function.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                    const limit = data.find((val)=>val.id_obat_masuk===$("#id_obat_masuk").val());
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
}
