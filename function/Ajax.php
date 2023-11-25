<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function Ajax($type, $data)
{
  ob_end_clean();
  global $conn, $arrYears, $cutiTahunan, $jmlTahunan, $user;
  $data = trimParam($data);
  switch ($type) {
    case 'CEK-JENISCUTI':
      $sql = "SELECT * FROM jenis_cuti WHERE id_jeniscuti='$data[id_jeniscuti]'";
      $jenisCuti = mysqli_fetch_assoc(mysqli_query($conn, $sql));
      if ($data['id_jeniscuti'] != '1') {
        $sql = "SELECT jc.*, COUNT(id_pengajuan) jml_pengajuan ,SUM(COALESCE(lama_cuti,0)) jml_hari FROM jenis_cuti jc 
          LEFT JOIN pengajuan_cuti pc USING (id_jeniscuti) 
            WHERE id_jeniscuti='$data[id_jeniscuti]'
              AND (id_pegawai='$user[id_pegawai]' OR id_pegawai IS NULL) 
              AND (status='Disetujui' OR status IS NULL) GROUP BY id_jeniscuti";
        $result = mysqli_query($conn, $sql);
        $res = $result ? mysqli_fetch_assoc($result) : [];
?>
        <div class="col px-2 mb-3 border">
          <table class="table table-sm table-borderless">
            <tr>
              <td colspan="2"><strong><?= $jenisCuti['nama_jeniscuti'] ?></strong></td>
            </tr>
            <tr>
              <td>Total pengajuan</td>
              <td><?= $res['jml_pengajuan'] ?? '0' ?> kali</td>
            </tr>
            <tr>
              <td>Total cuti</td>
              <td><?= $res['jml_hari'] ?? '0' ?> hari</td>
            </tr>
          </table>
        </div>
      <?php
        return;
      }
      $implodeYears = implode(', ', $arrYears);
      $sql = "SELECT jc.*, 
          YEAR(tanggal_modifikasi) tahun,
          COUNT(id_pengajuan) jml_pengajuan,
          SUM(COALESCE(lama_cuti,0)) jml_hari
        FROM jenis_cuti jc 
          LEFT JOIN pengajuan_cuti pc USING (id_jeniscuti) 
            WHERE id_jeniscuti='$data[id_jeniscuti]' 
              AND (id_pegawai='$user[id_pegawai]' OR id_pegawai IS NULL) 
              AND (YEAR(tanggal_modifikasi) IN ($implodeYears) OR YEAR(tanggal_modifikasi) IS NULL) 
              AND (status='Disetujui' OR status IS NULL) GROUP BY YEAR(tanggal_modifikasi)";
      $result = mysqli_query($conn, $sql);
      $dataCuti =  [
        'nama_jeniscuti' => $jenisCuti['nama_jeniscuti'],
      ];
      $cutiNow = 0;
      if ($result) {
        foreach ($result as $row) {
          if ($row['tahun'] !== NULL) {
            $sisa = $jmlTahunan;
            if ($row['tahun'] != date('Y')) {
              $sisa = $jmlTahunan - (int) $row['jml_hari'];
              $sisa = $sisa > ($jmlTahunan / 2) ? ($jmlTahunan / 2) : ($sisa < 0 ? 0 : $sisa);
            } else {
              $cutiNow = (int) $row['jml_hari'];
            }
            $cutiTahunan[$row['tahun']] = [
              'jml_pengajuan' => $row['jml_pengajuan'],
              'jml_hari' => $row['jml_hari'],
              'jml_sisa' => $sisa,
            ];
          }
        }
      }
      // unset($cutiTahunan2[date('Y')]);
      foreach ($cutiTahunan as $key => $value) {
        if ($cutiTahunan[$key]['jml_sisa'] == 0) continue;
        $sisaNow = $cutiTahunan[$key]['jml_sisa'] - $cutiNow;
        $cutiNow = $sisaNow >= 0 ? 0 : (-$sisaNow);
        $cutiTahunan[$key]['jml_sisa'] = $sisaNow < 0 ? 0 : $sisaNow;
      }
      $totalSisa = 0;
      foreach ($cutiTahunan as $row) {
        $totalSisa += $row['jml_sisa'];
      }
      $dataCuti['data'] = $cutiTahunan;
      ?>
      <div class="col px-2 mb-3 border">
        <table class="table table-sm table-borderless">
          <tr>
            <td colspan="3"><strong><?= $dataCuti['nama_jeniscuti'] ?></strong></td>
          </tr>
          <tr>
            <th>Tahun</th>
            <th>Sisa</th>
            <th>keterangan</th>
          </tr>
          <?php foreach ($dataCuti['data'] as $key => $val) { ?>
            <tr>
              <td><?= $key ?></td>
              <td><?= $val['jml_sisa'] ?></td>
              <td>-</td>
            </tr>
          <?php } ?>
        </table>
      </div>
<?php
      return $totalSisa;
      break;

    default:
      break;
  }
}
