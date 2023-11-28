<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function Jabatan($type, $data)
{
  global $conn;
  $data = trimParam($data);
  switch ($type) {
    case 'CREATE':
      $sql = "SELECT * FROM jabatan WHERE id_unitkerja='$data[id_unitkerja]' AND LOWER(nama_jabatan)='" . strtolower($data['nama_jabatan']) . "'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Jabatan sudah ada pada unit kerja tersebut!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      $data['hanya_satu'] = isset($data['hanya_satu']) ? '1' : '0';
      $keys = [];
      $values = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($keys, $key);
          array_push($values, "'$value'");
        }
      }
      $sql = "INSERT INTO jabatan(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
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

    case 'UPDATE':
      $sql = "SELECT * FROM jabatan WHERE id_jabatan<>'$data[id_jabatan]' AND id_unitkerja='$data[id_unitkerja]' AND LOWER(nama_jabatan)='" . strtolower($data['nama_jabatan']) . "'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Jabatan sudah ada pada unit kerja tersebut!',
          'type' => 'EDIT',
          'data' => $data,
        ];
        return;
      }
      $data['hanya_satu'] = isset($data['hanya_satu']) ? '1' : '0';
      $sets = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($sets, "$key = '$value'");
        } else {
          array_push($sets, "$key = NULL");
        }
      }
      // dd($sets);
      $sql = "UPDATE jabatan SET " . (implode(', ', $sets)) . " WHERE id_jabatan = '$data[id_jabatan]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => 'Berhasil mengubah data!',
        ];
      } else {
        $_SESSION['flash'] = [
          'status' => 'info',
          'msg' => 'Data tidak berubah!',
        ];
      }
      break;

    case 'DELETE':
      $sql = "DELETE FROM jabatan WHERE id_jabatan = '$data[id_jabatan]'";
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

    case 'GET-FREE':
      $sql =
        "SELECT * FROM (SELECT jb.*,uk.nama_unitkerja, COUNT(id_pegawai) jml_pegawai FROM jabatan jb 
          LEFT JOIN unit_kerja uk USING(id_unitkerja)
          LEFT JOIN pegawai USING(id_jabatan)
          GROUP BY jb.id_jabatan) tb
          WHERE NOT (hanya_satu='1' AND jml_pegawai=1)";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
      break;

    case 'ONE':
      $sql = "SELECT * FROM jabatan jb LEFT JOIN unit_kerja uk USING(id_unitkerja) WHERE hanya_satu='1'";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
      break;

    default:
      $sql = "SELECT * FROM jabatan jb LEFT JOIN unit_kerja uk USING(id_unitkerja) LEFT JOIN (SELECT tp.*,CONCAT(tb1.nama_jabatan, ' - ',tb1.nama_unitkerja) nama_pertama, CONCAT(tb2.nama_jabatan, ' - ',tb2.nama_unitkerja) nama_kedua FROM tamplate_persetujuan tp 
      LEFT JOIN (SELECT * FROM jabatan jb LEFT JOIN unit_kerja uk USING(id_unitkerja)) tb1 ON tp.persetujuan_pertama=tb1.id_jabatan
      LEFT JOIN (SELECT * FROM jabatan jb LEFT JOIN unit_kerja uk USING(id_unitkerja)) tb2 ON tp.persetujuan_kedua=tb2.id_jabatan) tp USING(id_tamplate)";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
  }
}
