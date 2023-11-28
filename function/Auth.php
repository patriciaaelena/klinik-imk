<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function Auth($type, $data)
{
  global $conn;
  switch ($type) {
    case 'REGISTER':
      $keys = [];
      $values = [];
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          array_push($keys, $key);
          array_push($values, "'$value'");
        }
      }
      $sql = "INSERT INTO pengguna(" . (implode(', ', $keys)) . ") VALUES(" . (implode(', ', $values)) . ")";
      $result = mysqli_query($conn, $sql);
      if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['flash'] = [
          'status' => 'success',
          'msg' => 'Berhasil menambah data!',
        ];
      }
      break;

    case 'UPDATE':
      if ($data['username'] === 'admin') return;
      $sql = "UPDATE pengguna SET username='$data[new]' WHERE username='$data[username]'";
      $result = mysqli_query($conn, $sql);
      break;

    case 'DELETE':
      if ($data === 'admin') return;
      $sql = "DELETE FROM pengguna WHERE username='$data'";
      $result = mysqli_query($conn, $sql);
      break;

    case 'LOGIN':
      $sql = "SELECT * FROM pengguna
          WHERE username = '$data[username]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($data['password'], $row['password'])) {
          if ($row['role'] != '2') {
            $row['nama_pegawai'] = ucwords(implode(" ", explode("-", $row['username'])));
            unset($row['id_pegawai']);
            if ($row['role'] == '1') {
              $nama_unitkerja = str_replace("-", " ", str_replace("admin-", "", $row['username']));
              $sql = "SELECT uk1.*, uk2.nama_unitkerja nama_induk FROM unit_kerja uk1 LEFT JOIN unit_kerja uk2 ON uk1.id_induk=uk2.id_unitkerja
                        WHERE LOWER(uk1.nama_unitkerja) = '$nama_unitkerja'";
              $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
              $row = array_merge($row, $result);
            }
            $_SESSION['auth'] = $row;
            $_SESSION['flash'] = [
              'status' => 'success',
              'msg' => "Selamat datang $row[nama_pegawai]!",
            ];
          } else {
            $sql = "SELECT * FROM pengguna pa 
                    LEFT JOIN pegawai pi USING(id_pegawai)
                    LEFT JOIN jabatan jb USING(id_jabatan)
                    LEFT JOIN unit_kerja uk USING(id_unitkerja)
                      WHERE username = '$data[username]'";
            $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
            $nama = $result['nama_pegawai'];
            $_SESSION['auth'] = $result;
            $sql = "SELECT * FROM tamplate_persetujuan 
                  WHERE persetujuan_pertama='$result[id_jabatan]' 
                    OR persetujuan_kedua='$result[id_jabatan]'";
            $result = mysqli_query($conn, $sql);
            $data = [];
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
              }
              $_SESSION['auth']['sign'] = $data;
            }
            $_SESSION['flash'] = [
              'status' => 'success',
              'msg' => "Selamat datang $nama!",
            ];
          }
          return;
        }
      }
      $_SESSION['flash'] = [
        'status' => 'error',
        'msg' => 'NIP atau Password tidak sesuai!',
      ];
      break;
    case 'LOGOUT':
      # code...
      unset($_SESSION['auth']);
      $_SESSION['flash'] = [
        'status' => 'success',
        'msg' => 'Berhasil logout!',
      ];
      break;
  }
}
