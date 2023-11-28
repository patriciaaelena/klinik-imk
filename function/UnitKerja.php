<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function UnitKerja($type, $data)
{
  global $conn;
  $data = trimParam($data);
  switch ($type) {
    case 'CREATE':
      $sql = "SELECT * FROM unit_kerja WHERE LOWER(nama_unitkerja)='" . strtolower($data['nama_unitkerja']) . "'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
          'status' => 'error',
          'msg' => 'Nama unit kerja sudah digunakan!',
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
      $sql = "INSERT INTO unit_kerja(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $param['username'] = "admin-" . strtolower(implode("-", explode(" ", $data['nama_unitkerja'])));
        $param['password'] = password_hash($param['username'], PASSWORD_DEFAULT);
        $param['role'] = "1";
        Auth('REGISTER', $param);
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
      $sql = "SELECT * FROM unit_kerja WHERE id_unitkerja<>'$data[id_unitkerja]' AND LOWER(nama_unitkerja)='" . strtolower($data['nama_unitkerja']) . "'";
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
      $sets = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($sets, "$key = '$value'");
        } else {
          array_push($sets, "$key = NULL");
        }
      }
      $sql = "SELECT*FROM unit_kerja WHERE id_unitkerja = '$data[id_unitkerja]'";
      $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
      $username = "admin-" . strtolower(implode("-", explode(" ", $result['nama_unitkerja'])));
      $sql = "UPDATE unit_kerja SET " . (implode(', ', $sets)) . " WHERE id_unitkerja = '$data[id_unitkerja]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        Auth('UPDATE', [
          'new' => "admin-" . strtolower(implode("-", explode(" ", $data['nama_unitkerja']))),
          'username' => $username,
        ]);
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
      $sql = "SELECT*FROM unit_kerja WHERE id_unitkerja = '$data[id_unitkerja]'";
      $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
      $username = "admin-" . strtolower(implode("-", explode(" ", $result['nama_unitkerja'])));
      $sql = "DELETE FROM unit_kerja WHERE id_unitkerja = '$data[id_unitkerja]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        Auth('DELETE', $username);
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
      $sql = "SELECT uk1.*, uk2.nama_unitkerja induk FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk = uk2.id_unitkerja";
      $result = mysqli_query($conn, $sql);
      $data = [];
      if (mysqli_num_rows($result) > 0)
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($data, $row);
        }
      return $data;
  }
}
