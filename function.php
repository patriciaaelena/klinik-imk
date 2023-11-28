<?php
if (!isset($halaman)) {
  require_once('./404.php');
  die;
}
$dep = "DEV";

if ($dep = "PROD1") {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  ini_set('html_errors', 1);
  ini_set('error_reporting', -1);
  error_reporting(E_ALL);
}
session_start();
ob_start();
date_default_timezone_set('Asia/Jakarta');
$locale = setlocale(LC_ALL, ['id_ID', 'id-ID', 'id']);

$fmt = new IntlDateFormatter(
  'id-ID',
  IntlDateFormatter::FULL,
  IntlDateFormatter::FULL,
  'Asia/Jakarta',
  IntlDateFormatter::GREGORIAN,
  "dd MMMM Y"
);
$fmtMY = new IntlDateFormatter(
  'id-ID',
  IntlDateFormatter::FULL,
  IntlDateFormatter::FULL,
  'Asia/Jakarta',
  IntlDateFormatter::GREGORIAN,
  "MMMM Y"
);

$jmlTahunan = 12;
$yearNow = (int) date("Y");
$arrYears = [];
for ($i = $yearNow - 2; $i <= $yearNow; $i++) {
  $arrYears[] = $i;
}
$cutiTahunan = [];
foreach ($arrYears as $val) {
  $cutiTahunan[strval($val)] = [
    'jml_pengajuan' => 0,
    'jml_hari' => 0,
    'jml_sisa' => strval($val) === date('Y') ? $jmlTahunan : ($jmlTahunan / 2),
  ];
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sitibel";

if ($dep = "PROD") {
  $servername = "localhost";
  $username = "u370369030_siibel";
  $password = "1n1S11b3l";
  $dbname = "u370369030_sitibel";
}

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection danger: " . mysqli_connect_error());
}

function dd()
{
  foreach (func_get_args() as $param) {
    var_dump($param);
    echo "<br>";
  }
  die;
}

function ddClean()
{
  ob_clean();
  foreach (func_get_args() as $param) {
    var_dump($param);
    echo "<br>";
  }
  die;
}

function trimParam($data)
{
  $res = [];
  foreach ($data as $key => $value) {
    $res[$key] = trim($value);
  }
  return $res;
}

require('./function/Auth.php');
