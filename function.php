<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('html_errors', 1);
ini_set('error_reporting', -1);
error_reporting(E_ALL);
session_start();
ob_start();
date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "klinik_imk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateCode($type, $id)
{
    return "$type-" . sprintf("%03d", (int)$id);
}

function auth($aksi, $data)
{
    switch ($aksi) {
        case 'login':
            global $conn;
            $query = "SELECT * FROM admin WHERE username='$data[username]'";
            $result = $conn->query($query);
            if ($result->num_rows == 1) {
                $res = $result->fetch_assoc();
                if (password_verify($data['password'], $res['password'])) {
                    $_SESSION['user'] = [
                        'id' => $res['id_adm'],
                        'nama' => $res['nama_adm'],
                    ];
                    $_SESSION['smess'] = [
                        'status' => true,
                        'alert' => 'success',
                        'msg' => "Selamat datang $res[nama_adm]!",
                    ];
                    return true;
                }
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Username atau password salah!",
            ];
            break;
        case 'logout':
            unset($_SESSION['user']);
            $_SESSION['smess'] = [
                'status' => true,
                'alert' => 'success',
                'msg' => "Berhasil logout!",
            ];
            break;
    }
    return false;
}

function dashboard()
{
    global $conn;
    $data = [];
    $query = "SELECT COUNT(id_adm) adm FROM admin";
    $result = $conn->query($query);
    $data['admin'] = $result->fetch_assoc()['adm'];
    $query = "SELECT COUNT(id_obat) obt FROM obat";
    $result = $conn->query($query);
    $data['obat'] = $result->fetch_assoc()['obt'];
    $query = "SELECT COUNT(id_obat) obat,COALESCE(SUM(awal),0) jumlah FROM obat_masuk WHERE MONTH(tgl_masuk)=MONTH(NOW())";
    $result = $conn->query($query);
    $data['masuk'] = $result->fetch_assoc();
    $query = "SELECT COUNT(id_obat) obat,COALESCE(SUM(IF(kedaluwarsa>0,jumlah,0)),0) kedaluwarsa,COALESCE(SUM(jumlah),0) jumlah FROM obat_keluar JOIN obat_masuk USING(id_obat_masuk) WHERE MONTH(tgl_keluar)=MONTH(NOW())";
    $result = $conn->query($query);
    $data['keluar'] = $result->fetch_assoc();
    return $data;
}

function admin($aksi, $data)
{
    global $conn;
    switch ($aksi) {
        case 'tambah':
            $query = "SELECT username FROM admin WHERE username='$data[username]'";
            $result = $conn->query($query);
            if ($result->num_rows != 1) {
                $data['password'] = password_hash($data['username'], PASSWORD_DEFAULT);
                $query = "INSERT INTO admin(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", $data) . "')";
                $result = $conn->query($query);
                if ($conn->affected_rows == 1) {
                    $_SESSION['smess'] = [
                        'status' => true,
                        'alert' => 'success',
                        'msg' => "Berhasil menambah pengguna!",
                    ];
                    return true;
                }
                $_SESSION['smess'] = [
                    'status' => false,
                    'alert' => 'error',
                    'msg' => "Gagal menambah pengguna!",
                ];
                return false;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal menambah pengguna! Username telah digunakan.",
            ];
            break;
        case 'ubah':
            $query = "UPDATE admin SET nama_adm='$data[nama_adm]',hp_adm='$data[hp_adm]' WHERE id_adm='$data[id_adm]'";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil mengubah pengguna!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'info',
                'msg' => "Data pengguna tidak berubah!",
            ];
            break;
        case 'hapus':
            $query = "DELETE FROM admin WHERE id_adm='$data[id_adm]'";
            try {
                $result = $conn->query($query);
            } catch (\Throwable $th) {
                $_SESSION['smess'] = [
                    'status' => false,
                    'alert' => 'error',
                    'msg' => "Gagal menghapus pengguna!",
                ];
            }
            if ($conn->affected_rows == 1) {
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menghapus pengguna!",
                ];
                return true;
            }
            break;
        default:
            $query = "SELECT id_adm,nama_adm,username,hp_adm FROM admin WHERE id_adm<>1";
            $result = $conn->query($query);
            $arrData = [];
            while ($row = $result->fetch_assoc()) {
                array_push($arrData, $row);
            }
            return $arrData;
            break;
    }
    return false;
}

function obat($aksi, $data)
{
    global $conn;
    switch ($aksi) {
        case 'tambah':
            $query = "INSERT INTO obat(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", $data) . "')";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $id = $conn->query("SELECT LAST_INSERT_ID() id")->fetch_assoc()['id'];
                $code = generateCode("OBT",$id);
                $result = $conn->query("UPDATE obat SET kode_obat='$code' WHERE id_obat='$id'");

                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menambah data obat!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal menambah data obat!",
            ];
            break;
        case 'ubah':
            $query = "UPDATE obat SET nama_obat='$data[nama_obat]',jenis='$data[jenis]',harga='$data[harga]' WHERE id_obat='$data[id_obat]'";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil mengubah data obat!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'info',
                'msg' => "Data obat tidak berubah!",
            ];
            break;
        case 'hapus':
            $query = "DELETE FROM obat WHERE id_obat='$data[id_obat]'";
            try {
                $result = $conn->query($query);
            } catch (\Throwable $th) {
                $_SESSION['smess'] = [
                    'status' => false,
                    'alert' => 'error',
                    'msg' => "Gagal menghapus data obat!",
                ];
            }
            if ($conn->affected_rows == 1) {
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menghapus data obat!",
                ];
                return true;
            }
            break;
        default:
            $query = "SELECT o.*,a.nama_adm FROM obat o JOIN admin a USING(id_adm)";
            $result = $conn->query($query);
            $arrData = [];
            while ($row = $result->fetch_assoc()) {
                array_push($arrData, $row);
            }
            return $arrData;
            break;
    }
    return false;
}

function obatMasuk($aksi, $data)
{
    global $conn;
    switch ($aksi) {
        case 'tambah':
            $query = "INSERT INTO obat_masuk(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", $data) . "')";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $id = $conn->query("SELECT LAST_INSERT_ID() id")->fetch_assoc()['id'];
                $code = generateCode("OBT/IN",$id);
                $result = $conn->query("UPDATE obat_masuk SET kode_obat_masuk='$code' WHERE id_obat_masuk='$id'");

                $query = "UPDATE obat SET stok=stok+$data[awal] WHERE id_obat='$data[id_obat]'";
                $result = $conn->query($query);
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menambah data obat masuk!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal menambah data obat masuk!",
            ];
            break;
        case 'ubah':
            $query = "UPDATE obat SET nama_obat='$data[nama_obat]',jenis='$data[jenis]',harga='$data[harga]' WHERE id_obat='$data[id_obat]'";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil mengubah data obat!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'info',
                'msg' => "Data obat tidak berubah!",
            ];
            break;
        case 'hapus':
            $query = "DELETE FROM obat_masuk WHERE id_obat_masuk='$data[id_obat_masuk]'";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $query = "UPDATE obat SET stok=stok-$data[awal] WHERE id_obat='$data[id_obat]'";
                $result = $conn->query($query);
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menghapus data obat masuk!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal menghapus data obat masuk!",
            ];
            break;
        default:
            $query = "SELECT*FROM (SELECT o.nama_obat,a.nama_adm,om.*,MAX(COALESCE(ok.kedaluwarsa, -1)) kedaluwarsa  FROM obat o JOIN obat_masuk om USING(id_obat) LEFT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON om.id_adm=a.id_adm GROUP BY id_obat_masuk) tb WHERE kedaluwarsa<>1";
            $result = $conn->query($query);
            $arrData = [];
            while ($row = $result->fetch_assoc()) {
                array_push($arrData, $row);
            }
            return $arrData;
            break;
    }
    return false;
}

function obatKeluar($aksi, $data)
{
    global $conn;
    switch ($aksi) {
        case 'tambah':
            $data['kedaluwarsa'] = '0';
            $id_obat = $data['id_obat'];
            unset($data['id_obat']);
            $query = "INSERT INTO obat_keluar(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", $data) . "')";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $id = $conn->query("SELECT LAST_INSERT_ID() id")->fetch_assoc()['id'];
                $code = generateCode("OBT/OUT",$id);
                $result = $conn->query("UPDATE obat_keluar SET kode_obat_keluar='$code' WHERE id_obat_keluar='$id'");

                $query = "UPDATE obat SET stok=stok-$data[jumlah] WHERE id_obat='$id_obat'";
                $result = $conn->query($query);
                $query = "UPDATE obat_masuk SET tersedia=tersedia-$data[jumlah] WHERE id_obat_masuk='$data[id_obat_masuk]'";
                $result = $conn->query($query);
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil menambah data obat keluar!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal menambah data obat keluar!",
            ];
            break;
        case 'kedaluwarsa':
            $data['kedaluwarsa'] = '1';
            $id_obat = $data['id_obat'];
            unset($data['id_obat']);
            $query = "INSERT INTO obat_keluar(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", $data) . "')";
            $result = $conn->query($query);
            if ($conn->affected_rows == 1) {
                $query = "UPDATE obat SET stok=stok-$data[jumlah] WHERE id_obat='$id_obat'";
                $result = $conn->query($query);
                $query = "UPDATE obat_masuk SET tersedia=0 WHERE id_obat='$data[id_obat_masuk]'";
                $result = $conn->query($query);
                $_SESSION['smess'] = [
                    'status' => true,
                    'alert' => 'success',
                    'msg' => "Berhasil mengeluarkan obat kedaluwarsa!",
                ];
                return true;
            }
            $_SESSION['smess'] = [
                'status' => false,
                'alert' => 'error',
                'msg' => "Gagal!",
            ];
            break;
        default:
            $query = "SELECT o.id_obat,o.nama_obat,a.nama_adm,om.id_obat_masuk,ok.*  FROM obat o JOIN obat_masuk om USING(id_obat) RIGHT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON a.id_adm=ok.id_adm";
            $result = $conn->query($query);
            $arrData = [];
            while ($row = $result->fetch_assoc()) {
                array_push($arrData, $row);
            }
            return $arrData;
            break;
    }
    return false;
}

function component($type, $data)
{
    global $conn;
    switch ($type) {
        case 'masuk':
            $query = "SELECT*FROM (SELECT o.nama_obat,om.*,MAX(COALESCE(ok.kedaluwarsa, -1)) kedaluwarsa, DATEDIFF(tgl_kdwrs, NOW()) selisih  FROM obat o JOIN obat_masuk om USING(id_obat) LEFT JOIN obat_keluar ok USING(id_obat_masuk) GROUP BY id_obat_masuk) tb WHERE kedaluwarsa<>1 AND tersedia<>0 AND id_obat='$data'";
            $result = $conn->query($query);
            $arrData = [];
            while ($row = $result->fetch_assoc()) {
                if ((int)$row['selisih'] >= 1) {
                    array_push($arrData, [
                        'id_obat_masuk' => $row['id_obat_masuk'],
                        'kode_obat_masuk' => $row['kode_obat_masuk'],
                        'tersedia' => $row['tersedia'],
                        'nama_obat' => $row['nama_obat'],
                        'selisih' => $row['selisih'],
                    ]);
                }
            }
            return $arrData;
            break;
    }
}

function laporan($table, $condition)
{
    global $conn;
    $res = "";
    $arrData = [];
    if ($table == "obat") {
        $query = "SELECT o.*,a.nama_adm FROM obat o JOIN admin a USING(id_adm)";
        $query .= $condition == 'Semua' ? "" : " WHERE jenis='$condition'";
    } else {
        if (gettype($condition) == 'array') {
            $sampai = date("Y-m-d", strtotime("+1 day", strtotime($condition['sampai'])));
            $dari = $condition['dari'];
            if ($table == "Masuk") {
                $query = "SELECT*FROM (SELECT o.nama_obat,a.nama_adm,om.*,MAX(COALESCE(ok.kedaluwarsa, -1)) kedaluwarsa  FROM obat o JOIN obat_masuk om USING(id_obat) LEFT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON om.id_adm=a.id_adm GROUP BY id_obat_masuk) tb WHERE tgl_masuk BETWEEN '$dari' AND '$sampai' ORDER BY nama_obat, tgl_masuk, tgl_kdwrs";
            } else {
                $query = "SELECT o.id_obat,o.nama_obat,a.nama_adm,om.id_obat_masuk,ok.*  FROM obat o JOIN obat_masuk om USING(id_obat) RIGHT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON a.id_adm=ok.id_adm WHERE tgl_keluar BETWEEN '$dari' AND '$sampai'";
            }
        } else {
            $sampai = in_array($condition, ["1", "3"]) ? date("Y-m-d", time() + 86400) : ($condition == "2" ? date("Y-m-d") : date("Y-m-01"));
            $dari = $condition == "1" ? date("Y-m-d") : ($condition == "2" ? date("Y-m-d", time() - 86400) : ($condition == "3" ? date("Y-m-01") : date('Y-m-01', strtotime('-1 month', strtotime(date("Y-m-d"))))));
            if ($table == "Masuk") {
                $query = "SELECT*FROM (SELECT o.nama_obat,a.nama_adm,om.*,MAX(COALESCE(ok.kedaluwarsa, -1)) kedaluwarsa  FROM obat o JOIN obat_masuk om USING(id_obat) LEFT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON om.id_adm=a.id_adm GROUP BY id_obat_masuk) tb WHERE tgl_masuk BETWEEN '$dari' AND '$sampai' ORDER BY nama_obat, tgl_masuk, tgl_kdwrs";
            } else {
                $query = "SELECT o.id_obat,o.nama_obat,a.nama_adm,om.id_obat_masuk,ok.*  FROM obat o JOIN obat_masuk om USING(id_obat) RIGHT JOIN obat_keluar ok USING(id_obat_masuk) LEFT JOIN admin a ON a.id_adm=ok.id_adm WHERE tgl_keluar BETWEEN '$dari' AND '$sampai'";
            }
        }
    }
    $res = $conn->query($query);
    foreach ($res as $row) {
        array_push($arrData, $row);
    }
    return [
        "data" => $arrData,
        "dari" => isset($dari) ? $dari : "",
        "sampai" => isset($sampai) ? $sampai : "",
    ];
}
