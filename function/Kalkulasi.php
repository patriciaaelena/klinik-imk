<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function Kalkulasi($type, $data)
{
  global $conn, $arrYears, $cutiTahunan, $jmlTahunan, $user;
  $data = trimParam($data);
  switch ($type) {
    case 'OTHER-FORM':
      $sql = "SELECT
          CONCAT(id_jeniscuti,'. ',nama_jeniscuti) nama_jeniscuti,
          SUM(COALESCE(lama_cuti,0)) jml_hari
        FROM jenis_cuti jc 
          LEFT JOIN pengajuan_cuti pc USING (id_jeniscuti) 
            WHERE id_jeniscuti<>'1'
              AND (id_pegawai='$data[id_pegawai]' OR id_pegawai IS NULL) 
              AND (tanggal_modifikasi<'$data[tanggal_modifikasi]' OR tanggal_modifikasi IS NULL)
              AND (status_pengajuan='Disetujui' OR status_pengajuan IS NULL) GROUP BY id_jeniscuti";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
      break;

    case 'TAHUNAN-FORM':
      $sql = "SELECT * FROM jenis_cuti WHERE id_jeniscuti='1'";
      $jenisCuti = mysqli_fetch_assoc(mysqli_query($conn, $sql));
      $jenisCuti = "$jenisCuti[id_jeniscuti]. $jenisCuti[nama_jeniscuti]";
      $yearNow = (int)date('Y', strtotime($data['tanggal_modifikasi']));
      $arrYears = [];
      for ($i = $yearNow - 2; $i <= $yearNow; $i++) {
        $arrYears[] = $i;
      }
      $implodeYears = implode(', ', $arrYears);
      $sql = "SELECT jc.*, 
          YEAR(tanggal_modifikasi) tahun,
          COUNT(id_pengajuan) jml_pengajuan,
          SUM(COALESCE(lama_cuti,0)) jml_hari
        FROM jenis_cuti jc 
          LEFT JOIN pengajuan_cuti pc USING (id_jeniscuti) 
            WHERE id_jeniscuti='1' 
              AND (id_pegawai='$data[id_pegawai]' OR id_pegawai IS NULL)
              AND (tanggal_modifikasi<'$data[tanggal_modifikasi]' OR tanggal_modifikasi IS NULL)
              AND (YEAR(tanggal_modifikasi) IN ($implodeYears) OR YEAR(tanggal_modifikasi) IS NULL) 
              AND (status_pengajuan='Disetujui' OR status_pengajuan IS NULL) GROUP BY YEAR(tanggal_modifikasi)";
      $result = mysqli_query($conn, $sql);
      $cutiNow = 0;
      if ($result) {
        foreach ($result as $row) {
          if ($row['tahun'] !== NULL) {
            $sisa = $jmlTahunan;
            if ($row['tahun'] != strval($yearNow)) {
              $sisa = $jmlTahunan - (int) $row['jml_hari'];
              $sisa = $sisa > ($jmlTahunan / 2) ? ($jmlTahunan / 2) : ($sisa < 0 ? 0 : $sisa);
            } else {
              $cutiNow = (int) $row['jml_hari'];
            }
            $cutiTahunan[$row['tahun']] = [
              'jml_hari' => $row['jml_hari'],
              'jml_sisa' => $sisa,
            ];
          }
        }
      }
      foreach ($cutiTahunan as $key => $value) {
        if ($cutiTahunan[$key]['jml_sisa'] == 0) continue;
        $sisaNow = $cutiTahunan[$key]['jml_sisa'] - $cutiNow;
        $cutiNow = $sisaNow >= 0 ? 0 : (-$sisaNow);
        $cutiTahunan[$key]['jml_sisa'] = $sisaNow < 0 ? 0 : $sisaNow;
      }
      $new = [];
      foreach ($cutiTahunan as $key => $value) {
        $new[] = [
          'tahun' => $key,
          'sisa' => $value['jml_sisa'],
          'ket' => '-',
        ];
      }
      array_unshift($new, ['Tahun', 'Sisa', 'Keterangan']);
      array_unshift($new, [$jenisCuti]);
      return $new;
      break;

    case 'TAHUNAN':
      $implodeYears = implode(', ', $arrYears);
      $sql = "SELECT jc.*, 
          YEAR(tanggal_modifikasi) tahun,
          COUNT(id_pengajuan) jml_pengajuan,
          SUM(COALESCE(lama_cuti,0)) jml_hari
        FROM jenis_cuti jc 
          LEFT JOIN pengajuan_cuti pc USING (id_jeniscuti) 
            WHERE id_jeniscuti='1' 
              AND (id_pegawai='$user[id_pegawai]' OR id_pegawai IS NULL) 
              AND (YEAR(tanggal_modifikasi) IN ($implodeYears) OR YEAR(tanggal_modifikasi) IS NULL) 
              AND (status_pengajuan='Disetujui' OR status_pengajuan IS NULL) GROUP BY YEAR(tanggal_modifikasi)";
      $result = mysqli_query($conn, $sql);
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
              'jml_hari' => $row['jml_hari'],
              'jml_sisa' => $sisa,
            ];
          }
        }
      }
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
      return $totalSisa;
      break;

    default:
      break;
  }
}
