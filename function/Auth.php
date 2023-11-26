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
    case 'LOGIN':
      $sql = "SELECT * FROM pengguna pa 
        LEFT JOIN pegawai pi USING(id_pegawai)
        LEFT JOIN jabatan jb USING(id_jabatan)
        LEFT JOIN unit_kerja uk USING(id_unitkerja)
          WHERE username = '$data[username]'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($data['password'], $row['password'])) {
          $_SESSION['auth'] = $row;
          $_SESSION['flash'] = [
            'status' => 'success',
            'msg' => "Selamat datang " . ($row['id_pegawai'] !== NULL ? $row['nama_pegawai'] : $row['username']) . "!",
          ];
          $sql = "SELECT * FROM tamplate_persetujuan 
                  WHERE persetujuan_pertama='$row[id_jabatan]' 
                    OR persetujuan_kedua='$row[id_jabatan]'";
          $result = mysqli_query($conn, $sql);
          $data = [];
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $data[] = $row;
            }
            $_SESSION['auth']['sign'] = $data;
            echo "asdasd";
          }
          // dd($data);
          dd($_SESSION['auth']);
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
