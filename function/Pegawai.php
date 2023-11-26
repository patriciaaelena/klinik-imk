<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function Pegawai($type, $data)
{
  global $conn;
  $data = trimParam($data);
  switch ($type) {
    case 'CREATE':
      $sql = "SELECT * FROM pegawai WHERE nik='$data[nik]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'NIK sudah terdaftar!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      $sql = "SELECT * FROM pegawai WHERE nip='$data[nip]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'NIP sudah terdaftar!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      if ($data['status'] === "PNS" && empty($data['nip'])) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'PNS harus memiliki NIP!',
          'type' => 'ADD',
          'data' => $data,
        ];
        return;
      }
      if ($data['status'] === "TEKON" && !empty($data['nip'])) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Tenaga Kontrak tidak memiliki NIP!',
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
      $sql = "INSERT INTO pegawai(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $param['username'] = empty($data['nip']) ? $data['nik'] : $data['nip'];
        $param['password'] = password_hash($param['username'], PASSWORD_DEFAULT);
        $param['role'] = "2";
        $param['id_pegawai'] = mysqli_insert_id($conn);
        Auth('REGISTER', $param);
      } else {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Gagal menambah data!',
        ];
      }
      break;

    case 'UPDATE':
      $sql = "SELECT * FROM jabatan WHERE id_jabatan<>'$data[id_jabatan]' AND id_unitkerja='$data[id_unitkerja]' AND LOWER(nama_jabatan)='" . strtolower($data['nama_jabatan']) . "'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Nama unit kerja sudah digunakan!',
          'type' => 'EDIT',
          'data' => $data,
        ];
        return;
      }
      $data['hanya_satu'] = isset($data['hanya_satu']) ? '1' : '0';
      $sets = [];
      foreach ($data as $key => $value) {
        array_push($sets, "$key = '$value'");
      }
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

    default:
      $sql = "SELECT pg.*,jb.*,CONCAT(uk.nama_unitkerja, ' ', uk.nama_induk) nama_unitkerja FROM pegawai pg LEFT JOIN jabatan jb USING(id_jabatan) LEFT JOIN (
                SELECT uk1.*, COALESCE(uk2.nama_unitkerja, '') nama_induk FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
              ) uk USING(id_unitkerja)";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
  }
}
