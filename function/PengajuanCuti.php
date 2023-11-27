<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function PengajuanCuti($type, $data)
{
  global $conn, $user, $fmt;
  $data = trimParam($data);
  switch ($type) {
    case 'CREATE':
      if ($data['id_jeniscuti'] == '1') {
        $limit = Kalkulasi('TAHUNAN', $data);
        if ((int)$data['lama_cuti'] > $limit) {
          $_SESSION['flash'] = [
            'status' => 'error',
            'msg' => "Sisa cuti tahunan tidak mencukupi! (Sisa cuti $limit hari.)",
            'type' => 'ADD',
            'data' => $data,
          ];
          return;
        }
      }
      $data['id_pegawai'] = $user['id_pegawai'];
      $data['no_hp'] = $user['no_hp'];
      $date = new DateTime($data['mulai_cuti']);
      $date->modify("+" . ((int)$data['lama_cuti'] - 1) . " weekdays");
      $data['selesai_cuti'] = $date->format("Y-m-d");
      $keys = [];
      $values = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($keys, $key);
          array_push($values, $value === NULL ? NULL : "'$value'");
        }
      }
      $sql = "INSERT INTO pengajuan_cuti(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => 'Berhasil mengajukan cuti!',
        ];
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Gagal menambah data!',
        ];
      }
      break;

    case 'PERSETUJUAN':
      $posisi = '';
      if (password_verify("ttd_pertama", $data['validate'])) {
        $posisi = "ttd_pertama";
      } else if (password_verify("ttd_kedua", $data['validate'])) {
        $posisi = "ttd_kedua";
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Persetujuan gagal! Mohon coba kembali.validate',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      if (!in_array($data['status_pengajuan'], ['Disetujui', 'Perubahan', 'Ditangguhkan', 'Tidak Disetujui'])) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Persetujuan gagal! Mohon coba kembali.state',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      if ($data['status_pengajuan'] !== 'Disetujui' && empty($data['catatan_cuti'])) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Selain disetujui, catatan harus diisi!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      $sql = '';
      if ($data['status_pengajuan'] !== 'Disetujui') {
        $catatan = htmlspecialchars($data['catatan_cuti']);
        $sql = "UPDATE pengajuan_cuti SET status_pengajuan='$data[status_pengajuan]', catatan_cuti='$catatan' WHERE id_pengajuan='$data[id_pengajuan]'";
      } else {
        $now = date("Y-m-d");
        $set = "$posisi='$now'";
        if ($posisi === 'ttd_kedua') {
          $set .= ",status_pengajuan='$data[status_pengajuan]'";
        }
        $sql = "UPDATE pengajuan_cuti SET $set WHERE id_pengajuan='$data[id_pengajuan]'";
      }
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => "Persetujuan permohonan berhasil $data[status_pengajuan]",
        ];
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Persetujuan permohonan gagal!',
        ];
      }
      break;

    case 'GET-ONE-SIMPLE':
      $arrCondition = [];
      foreach ($data as $key => $val) {
        if ($val === 'Disetujui') {
          $arrCondition[] = "selesai_cuti>='" . date('Y-m-d') . "'";
        };
        $arrCondition[] = "$key='$val'";
      }
      $sql = "SELECT * FROM pengajuan_cuti " . (count($arrCondition) > 0 ? " WHERE " . implode(' AND ', $arrCondition) : "");
      $result = mysqli_query($conn, $sql);
      if ($result) {
        return mysqli_fetch_assoc($result);
      }
      return false;

    case 'GET-ONE':
      $arrCondition = [];
      foreach ($data as $key => $val) {
        $arrCondition[] = "$key='$val'";
      }
      $sql = "SELECT * FROM pegawai pg LEFT JOIN jabatan jb USING(id_jabatan)
                    LEFT JOIN (
                      SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                      FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                    ) uk USING(id_unitkerja)
                    LEFT JOIN 
                      (
                        SELECT tp.*, nama_pertama, nik_pertama, nip_pertama, CONCAT(tb1.nama_jabatan, ' - ',tb1.nama_unitkerja) jabatan_pertama, nama_kedua, nik_kedua, nip_kedua, CONCAT(tb2.nama_jabatan, ' - ',tb2.nama_unitkerja) jabatan_kedua FROM tamplate_persetujuan tp 
                        LEFT JOIN (
                          SELECT * FROM (
                            SELECT jb.*, pg.nama_pegawai nama_pertama, pg.nik nik_pertama, pg.nip nip_pertama FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
                          ) jb 
                          LEFT JOIN (
                            SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                            FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                          ) uk USING(id_unitkerja)
                        ) tb1 ON tp.persetujuan_pertama=tb1.id_jabatan
                        LEFT JOIN (
                          SELECT * FROM (
                            SELECT jb.*, pg.nama_pegawai nama_kedua, pg.nik nik_kedua, pg.nip nip_kedua FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
                          ) jb
                          LEFT JOIN (
                            SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                            FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                          ) uk USING(id_unitkerja)
                        ) tb2 ON tp.persetujuan_kedua=tb2.id_jabatan
                      ) tb1 USING (id_tamplate)
                      JOIN pengajuan_cuti USING(id_pegawai) JOIN jenis_cuti USING(id_jeniscuti)
                  " . (count($arrCondition) > 0 ? " WHERE " . implode(' AND ', $arrCondition) : "");
      $result = mysqli_query($conn, $sql);
      if ($result) {
        return mysqli_fetch_assoc($result);
      }
      return false;

    case 'AUTH-ONE':
      $arrCondition = [];
      foreach ($data as $key => $val) {
        $arrCondition[] = "$key='$val'";
      }
      $arrCondition[] = "(persetujuan_pertama='$user[id_jabatan]' OR persetujuan_kedua='$user[id_jabatan]')";
      $where = count($arrCondition) > 0 ? " WHERE " . implode(' AND ', $arrCondition) : "";
      $sql = "SELECT * FROM pengajuan_cuti 
              JOIN jenis_cuti USING(id_jeniscuti) 
              JOIN pegawai pg USING(id_pegawai) 
              JOIN jabatan jb USING(id_jabatan)
              JOIN (
                SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
              ) uk USING(id_unitkerja)
              JOIN (
                SELECT tp.*, nama_pertama, nik_pertama, nip_pertama, CONCAT(tb1.nama_jabatan, ' - ',tb1.nama_unitkerja) jabatan_pertama, nama_kedua, nik_kedua, nip_kedua, CONCAT(tb2.nama_jabatan, ' - ',tb2.nama_unitkerja) jabatan_kedua FROM tamplate_persetujuan tp 
                LEFT JOIN (
                  SELECT * FROM (
                    SELECT jb.*, pg.nama_pegawai nama_pertama, pg.nik nik_pertama, pg.nip nip_pertama FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
                  ) jb 
                  LEFT JOIN (
                    SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                    FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                  ) uk USING(id_unitkerja)
                ) tb1 ON tp.persetujuan_pertama=tb1.id_jabatan
                LEFT JOIN (
                  SELECT * FROM (
                    SELECT jb.*, pg.nama_pegawai nama_kedua, pg.nik nik_kedua, pg.nip nip_kedua FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
                  ) jb
                  LEFT JOIN (
                    SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                    FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                  ) uk USING(id_unitkerja)
                ) tb2 ON tp.persetujuan_kedua=tb2.id_jabatan
              ) tp USING(id_tamplate)
              $where
              ORDER BY tanggal_modifikasi DESC";
      // var_dump($sql);
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;

    case 'AUTH-LIST':
      $arrCondition = [];
      foreach ($data as $key => $val) {
        $arrCondition[] = "$key='$val'";
      }
      $arrCondition[] = "(persetujuan_pertama='$user[id_jabatan]' OR persetujuan_kedua='$user[id_jabatan]')";
      $where = count($arrCondition) > 0 ? " WHERE " . implode(' AND ', $arrCondition) : "";
      $sql = "SELECT * FROM pengajuan_cuti 
              JOIN jenis_cuti USING(id_jeniscuti) 
              JOIN pegawai pg USING(id_pegawai) 
              JOIN jabatan jb USING(id_jabatan)
              JOIN (
                SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
              ) uk USING(id_unitkerja)
              JOIN tamplate_persetujuan USING(id_tamplate)
                $where
                ORDER BY tanggal_modifikasi DESC";
      // var_dump($sql);
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;

    default:
      $arrCondition = [];
      foreach ($data as $key => $val) {
        $arrCondition[] = "$key='$val'";
      }
      $where = count($arrCondition) > 0 ? " WHERE " . implode(' AND ', $arrCondition) : "";
      $sql = "SELECT * FROM pengajuan_cuti 
              JOIN jenis_cuti USING(id_jeniscuti) 
              JOIN pegawai pg USING(id_pegawai) 
              JOIN jabatan jb USING(id_jabatan)
              JOIN (
                SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
                FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
              ) uk USING(id_unitkerja)
              JOIN tamplate_persetujuan USING(id_tamplate)
                $where
                ORDER BY tanggal_modifikasi DESC";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
  }
}
