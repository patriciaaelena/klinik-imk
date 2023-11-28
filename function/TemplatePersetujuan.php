<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function TemplatePersetujuan($type, $data)
{
  global $conn;
  $data = trimParam($data);
  switch ($type) {
    case 'CREATE':
      $sql = "SELECT * FROM tamplate_persetujuan WHERE LOWER(nama_tamplate)='" . strtolower($data['nama_tamplate']) . "'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Nama template telah digunakan!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      $keys = [];
      $values = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($keys, $key);
          array_push($values, "'$value'");
        }
      }
      $sql = "INSERT INTO tamplate_persetujuan(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => 'Berhasil menambah data!',
        ];
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Gagal menambah data!',
          'type' => 'ADD',
          'data' => $data,
        ];
      }
      break;

    case 'DELETE':
      $sql = "DELETE FROM tamplate_persetujuan WHERE id_tamplate = '$data[id_tamplate]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => 'Berhasil menghapus data!',
        ];
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Gagal menghapus data!',
        ];
      }
      break;

    case 'GET-PERJOB':
      $sql = "SELECT * FROM jabatan jb LEFT JOIN 
        (
          SELECT tp.*, nama_pertama, nik_pertama, nip_pertama, CONCAT(tb1.nama_jabatan, ' - ',tb1.nama_unitkerja) jabatan_pertama, nama_kedua, nik_kedua, nip_kedua, CONCAT(tb2.nama_jabatan, ' - ',tb2.nama_unitkerja) jabatan_kedua FROM tamplate_persetujuan tp 
          LEFT JOIN (
            SELECT * FROM (
              SELECT jb.*, pg.nama_pegawai nama_pertama, pg.nik nik_pertama, pg.nip nip_pertama FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
            ) jb LEFT JOIN (
              SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
              FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
            ) uk USING(id_unitkerja)
          ) tb1 ON tp.persetujuan_pertama=tb1.id_jabatan
          LEFT JOIN (
            SELECT * FROM (
              SELECT jb.*, pg.nama_pegawai nama_kedua, pg.nik nik_kedua, pg.nip nip_kedua FROM jabatan jb LEFT JOIN pegawai pg USING(id_jabatan)
            ) jb LEFT JOIN (
              SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
              FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
            ) uk USING(id_unitkerja)
          ) tb2 ON tp.persetujuan_kedua=tb2.id_jabatan
        ) tb1 USING (id_tamplate) WHERE id_jabatan='$data[id_jabatan]'";
      // dd($sql);
      $result = mysqli_query($conn, $sql);
      return mysqli_fetch_assoc($result);

    default:
      $sql = "SELECT tp.*,CONCAT(tb1.nama_jabatan, ' - ',tb1.nama_unitkerja) nama_pertama, CONCAT(tb2.nama_jabatan, ' - ',tb2.nama_unitkerja) nama_kedua FROM tamplate_persetujuan tp 
      LEFT JOIN (SELECT * FROM jabatan jb LEFT JOIN (
          SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
          FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
        ) uk USING(id_unitkerja)) tb1 ON tp.persetujuan_pertama=tb1.id_jabatan
      LEFT JOIN (SELECT * FROM jabatan jb LEFT JOIN (
          SELECT uk1.id_unitkerja, CONCAT(uk1.nama_unitkerja,' ',COALESCE(uk2.nama_unitkerja, '')) nama_unitkerja
          FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
        ) uk USING(id_unitkerja)) tb2 ON tp.persetujuan_kedua=tb2.id_jabatan";
      // dd($sql);
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
  }
}
