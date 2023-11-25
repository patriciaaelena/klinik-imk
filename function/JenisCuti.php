<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
function JenisCuti()
{
  global $conn;
  $sql = "SELECT * FROM jenis_cuti";
  $result = mysqli_query($conn, $sql);
  $data = [];
  if (mysqli_num_rows($result) > 0)
    while ($row = mysqli_fetch_assoc($result)) {
      array_push($data, $row);
    }
  return $data;
}
