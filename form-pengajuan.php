<?php
if (!isset($_GET['id'])) {
  require_once('./404.php');
  die;
}
$halaman = "cetak";
require_once('function.php');
require_once('./function/JenisCuti.php');
require_once('./function/PengajuanCuti.php');
require_once('./function/Kalkulasi.php');
$rows = JenisCuti();
$rowCount = 0;
$temp = [];
$taken = [];
foreach ($rows as $row) {
  if ($rowCount === 1) {
    $temp[] = [
      'id' => $row['id_jeniscuti'],
      'nama' => "$row[id_jeniscuti]. $row[nama_jeniscuti]",
    ];
    $taken[] = $temp;
    $temp = [];
    $rowCount = 0;
  } else {
    $temp[] = [
      'id' => $row['id_jeniscuti'],
      'nama' => "$row[id_jeniscuti]. $row[nama_jeniscuti]",
    ];
    $rowCount++;
  }
}
$pengajuan = PengajuanCuti('GET-ONE', ['id_pengajuan' => $_GET['id']]);
if ($pengajuan == false) {
  require_once('./404.php');
  die;
}
$ttd = "<div class='custom-center'>&#x2714;</div>";
$state = ['Disetujui', 'Perubahan', 'Ditangguhkan', 'Tidak Disetujui'];
$state1 = ['Disetujui', 'Perubahan', 'Ditangguhkan', 'Tidak Disetujui'];
$msg1 = ['<br>', '<br>', '<br>', '<br>'];
$state2 = ['Disetujui', 'Perubahan', 'Ditangguhkan', 'Tidak Disetujui'];
$msg2 = ['<br>', '<br>', '<br>', '<br>'];

if ($pengajuan['ttd_pertama'] !== NULL) {
  $idx = 0;
  $state1 = array_map(function ($value): string {
    global $idx, $msg1;
    $msg1[$idx] = $value === "Disetujui" ? "&#x2714;" : "";
    $idx++;
    $v = strtoupper($value);
    return ($value === "Disetujui" ? $v : "<del>$v</del>");
  }, $state1);
} else {
  if ($pengajuan['status_pengajuan'] === 'Proses') {
    $state1 = array_map(function ($value): string {
      $v = strtoupper($value);
      return ($v);
    }, $state1);
  } else {
    $idx = 0;
    $state1 = array_map(function ($value): string {
      global $idx, $msg1, $pengajuan;
      $msg1[$idx] = $value === $pengajuan['status_pengajuan'] ? $pengajuan['catatan_cuti'] : "";
      $idx++;
      $v = strtoupper($value);
      return ($value === $pengajuan['status_pengajuan'] ? $v : "<del>$v</del>");
    }, $state1);
  }
}
if ($pengajuan['ttd_kedua'] !== NULL) {
  $idx = 0;
  $state2 = array_map(function ($value): string {
    global $idx, $msg2;
    $msg2[$idx] = $value === "Disetujui" ? "&#x2714;" : "";
    $idx++;
    $v = strtoupper($value);
    return ($value === "Disetujui" ? $v : "<del>$v</del>");
  }, $state2);
} else {
  if ($pengajuan['status_pengajuan'] === 'Proses') {
    $state2 = array_map(function ($value): string {
      $v = strtoupper($value);
      return ($v);
    }, $state2);
  } else {
    if ($pengajuan['ttd_pertama'] !== NULL) {
      $idx = 0;
      $state2 = array_map(function ($value): string {
        global $idx, $msg2, $pengajuan;
        $msg2[$idx] = $value === $pengajuan['status_pengajuan'] ? $pengajuan['catatan_cuti'] : "";
        $idx++;
        $v = strtoupper($value);
        return ($value === $pengajuan['status_pengajuan'] ? $v : "<del>$v</del>");
      }, $state2);
    }
  }
}
$tahunan = Kalkulasi('TAHUNAN-FORM', [
  'id_pegawai' => $pengajuan['id_pegawai'],
  'tanggal_modifikasi' => $pengajuan['tanggal_modifikasi'],
]);
$lain = Kalkulasi('OTHER-FORM', [
  'id_pegawai' => $pengajuan['id_pegawai'],
  'tanggal_modifikasi' => $pengajuan['tanggal_modifikasi'],
]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <!-- <link rel="stylesheet" href="./dist/css/adminlte.css"> -->
  <link rel="stylesheet" href="./dist/css/form.css">
  <script src="./plugins/jquery/jquery.js"></script>
  <script src="./plugins/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="./dist/js/adminlte.js"></script>
  <script>
    $(document).ready(() => {
      <?php if (!isset($_GET['iframe'])) { ?>
        window.print();
      <?php } ?>
    });
  </script>
</head>

<body>
  <div class="main d-flex flex-column gap-3">
    <header class="d-flex justify-content-end">
      <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column">
          <div>ANAK LAMPIRAN 1.b</div>
          <div>PERATURAN BADAN KEPEGAWAIAN NEGARA</div>
          <div>REPUBLIK INDONESIA</div>
          <div>NOMOR 24 TAHUN 2017</div>
          <div>TENTANG</div>
          <div>TATA CARA PEMBERIAN CUTI PEGAWAI NEGARA SIPIL</div>
        </div>
        <table>
          <tr>
            <td class="no-padding">Palangka Raya,</td>
            <td class="no-padding"><?= $fmt->format(strtotime($pengajuan['tanggal_modifikasi'])) ?></td>
          </tr>
          <tr>
            <td class="no-padding"></td>
            <td class="no-padding">Kepada</td>
          </tr>
          <tr>
            <td class="no-padding text-center">Yth.</td>
            <td class="no-padding"><?= $pengajuan['nama_kedua'] ?></td>
          </tr>
          <tr>
            <td class="no-padding"></td>
            <td class="no-padding">Universitas Palangka Raya</td>
          </tr>
          <tr>
            <td class="no-padding"></td>
            <td class="no-padding">di</td>
          </tr>
          <tr>
            <td class="no-padding"></td>
            <td class="no-padding">PALANGKA RAYA</td>
          </tr>

        </table>
      </div>
    </header>
    <main class="d-flex flex-column gap-4">
      <div class="col text-center">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="4">I. DATA PEGAWAI</td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark">Nama</td>
          <td class="px-only-3 border border-dark"><?= $pengajuan['nama_pegawai'] ?></td>
          <td class="px-only-3 border border-dark"><?= empty($pengajuan['nip']) ? "NIK" : "NIP" ?></td>
          <td class="px-only-3 border border-dark"><?= empty($pengajuan['nip']) ? $pengajuan['nik'] : $pengajuan['nip'] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark">Jabatan</td>
          <td class="px-only-3 border border-dark"><?= $pengajuan['nama_jabatan'] ?></td>
          <td class="px-only-3 border border-dark cell-shrink">Masa Kerja</td>
          <?php
          $date1 = new DateTime($pengajuan['mulai_kerja']);
          $date2 = new DateTime();
          $interval = $date1->diff($date2);
          $d[] = $interval->y > 0 ? $interval->y . " tahun" : "";
          $d[] = $interval->m > 0 ? $interval->m . " bulan" : "";
          $d[] = $interval->d > 0 ? $interval->d . " hari" : "";
          $tgl = [];
          foreach ($d as $v) {
            if (!empty($v)) {
              $tgl[] = $v;
            }
          }
          ?>
          <td class="px-only-3 border border-dark"><?= implode(", ", $tgl) ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark cell-shrink">Unit Kerja</td>
          <td class="px-only-3 border border-dark" colspan="3"><?= $pengajuan['nama_unitkerja'] ?> Universitas Palangka Raya</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="4">II. JENIS CUTI YANG DIAMBIL **</td>
        </tr>
        <?php foreach ($taken as $row) { ?>
          <tr>
            <td class="px-only-3 border border-dark"><?= $row[0]['nama'] ?></td>
            <td class="px-only-3 border border-dark text-center"><?= $row[0]['id'] === $pengajuan['id_jeniscuti'] ? "&#x2714;" : "" ?></td>
            <td class="px-only-3 border border-dark"><?= $row[1]['nama'] ?></td>
            <td class="px-only-3 border border-dark text-center"><?= $row[1]['id'] === $pengajuan['id_jeniscuti'] ? "&#x2714;" : "" ?></td>
          </tr>
        <?php } ?>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="4">III. ALASAN CUTI</td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark"><?= $pengajuan['alasan'] ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="6">IV. LAMANYA CUTI</td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark cell-shrink">Selama</td>
          <td class="px-only-3 border border-dark"><?= $pengajuan['lama_cuti'] ?> hari</td>
          <td class="px-only-3 border border-dark cell-shrink">mulai tanggal</td>
          <td class="px-only-3 border border-dark"><?= $fmt->format(strtotime($pengajuan['mulai_cuti'])) ?></td>
          <td class="px-only-3 border border-dark cell-shrink">s/d</td>
          <td class="px-only-3 border border-dark"><?= $fmt->format(strtotime($pengajuan['selesai_cuti'])) ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="5">V. CATATAN CUTI</td>
        </tr>
        <?php for ($i = 0; $i < 5; $i++) {
        ?>
          <tr>
            <?php if (count($tahunan[$i]) < 3) { ?>
              <td class="px-only-3 border border-dark" colspan="3"><?= $tahunan[$i][0] ?></td>
            <?php } else { ?>
              <?php unset($tahunan[$i]['jml']); ?>
              <?php foreach ($tahunan[$i] as $key => $val) { ?>
                <td class="px-only-3 border border-dark"><?= $key === "1" ? strtoupper($val) : $val ?><?= $key === "sisa"  ? " hari" : "" ?></td>
              <?php } ?>
            <?php } ?>
            <?php foreach ($lain[$i] as $key => $val) { ?>
              <td class="px-only-3 border border-dark"><?= strtoupper($val) ?><?= $key === "jml_hari"  ? " hari" : "" ?></td>
            <?php } ?>
          </tr>
        <?php
        } ?>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="2">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
          <td class="px-only-3 border border-dark" colspan="2"><?= $pengajuan['alamat_cuti'] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border border-dark"></td>
          <td class="px-only-3 border border-dark cell-shrink" colspan="2">TELP. <?= $pengajuan['no_hp'] ?></td>
          <td class="px-only-3 border border-dark"></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="3">Hormat saya</td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark"><br><br></td>
          <td class="px-only-3 border-inline border-dark position-relative" colspan="3"><?= $ttd ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="3"><?= $pengajuan['nama_pegawai'] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline-end border-dark"></td>
          <td class="px-only-3 border-inline-end border-dark text-center" colspan="3"><?= empty($pengajuan['nip']) ? "NIK." : "NIP." ?> <?= empty($pengajuan['nip']) ? $pengajuan['nik'] : $pengajuan['nip'] ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG **</td>
        </tr>
        <tr>
          <?php foreach ($state1 as $key => $row) { ?>
            <td class="px-only-3 border border-dark position-relative"><?= $row ?></td>
          <?php } ?>
        </tr>
        <tr>
          <?php foreach ($msg1 as $key => $row) { ?>
            <td class="px-only-3 border border-dark position-relative <?= $key == '0' ? "text-center" : "" ?>"><?= $row ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="2"><?= explode(" - ", $pengajuan['jabatan_pertama'])[0] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"><br><br></td>
          <td class="px-only-3 border-inline border-dark position-relative" colspan="2"><?= $pengajuan['ttd_pertama'] !== NULL ? $ttd : "" ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="2"><?= $pengajuan['nama_pertama'] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline-end border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline-end border-dark text-center" colspan="2"><?= empty($pengajuan['nip_pertama']) ? "NIK." : "NIP." ?> <?= empty($pengajuan['nip_pertama']) ? $pengajuan['nik_pertama'] : $pengajuan['nip_pertama'] ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="px-only-3 border border-dark" colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI **</td>
        </tr>
        <tr>
          <?php foreach ($state2 as $key => $row) { ?>
            <td class="px-only-3 border border-dark position-relative"><?= $row ?></td>
          <?php } ?>
        </tr>
        <tr>
          <?php foreach ($msg2 as $key => $row) { ?>
            <td class="px-only-3 border border-dark position-relative <?= $key == '0' ? "text-center" : "" ?>"><?= $row ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="2"><?= explode(" - ", $pengajuan['jabatan_kedua'])[0] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"><br><br></td>
          <td class="px-only-3 border-inline border-dark position-relative" colspan="2"><?= $pengajuan['ttd_kedua'] !== NULL ? $ttd : "" ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline border-dark text-center" colspan="2"><?= $pengajuan['nama_kedua'] ?></td>
        </tr>
        <tr>
          <td class="px-only-3 border-inline-end border-dark" colspan="2"></td>
          <td class="px-only-3 border-inline-end border-dark text-center" colspan="2"><?= empty($pengajuan['nip_kedua']) ? "NIK." : "NIP." ?> <?= empty($pengajuan['nip_kedua']) ? $pengajuan['nik_kedua'] : $pengajuan['nip_kedua'] ?></td>
        </tr>
      </table>
    </main>
    <footer>
      <table>
        <tr>
          <td class="px-only-3" colspan="2">Catatan :</td>
        </tr>
        <tr>
          <td class="px-only-3">*</td>
          <td class="px-only-3">coret yang tidak perlu</td>
        </tr>
        <tr>
          <td class="px-only-3">**</td>
          <td class="px-only-3">Pilih salah satu dengan member tanda centang ( { )</td>
        </tr>
        <tr>
          <td class="px-only-3">***</td>
          <td class="px-only-3">diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class="px-only-3">****</td>
          <td class="px-only-3">di beri tanda centang dan alasannya</td>
        </tr>
        <tr>
          <td class="px-only-3">N-</td>
          <td class="px-only-3">= Cuti Tahunan berjalan</td>
        </tr>
        <tr>
          <td class="px-only-3">N- 1</td>
          <td class="px-only-3">= Sisa cuti I tahun sebelumnya</td>
        </tr>
        <tr>
          <td class="px-only-3">N- 2</td>
          <td class="px-only-3">= Sisa cuti 2 tahun sebelumnya</td>
        </tr>
      </table>
      <div class="logo-footer d-flex align-items-center">
        <img src="./assets/logo-blu.png" alt="" width="70px">
      </div>
    </footer>
  </div>
  <!-- <script>
    const rescale = (elem) => {

      var height = parseInt(elem.css('height'));
      var width = parseInt(elem.css('width'));
      var scalex = parseFloat(elem.attr('scalex'));
      var scaley = parseFloat(elem.attr('scaley'));

      if (!elem.hasClass('rescaled')) {
        var ratioX = scalex;
        var ratioY = scaley;
      } else {
        var ratioX = 1;
        var ratioY = 1;
      }

      elem.toggleClass('rescaled');
      elem.css('-webkit-transform', 'scale(' + ratioX + ', ' + ratioY + ')');
      elem.parent().css('width', parseInt(width * ratioX) + 'px');
      elem.parent().css('height', parseInt(height * ratioY) + 'px');
    }
    $(document).ready(() => {
      // $('.scalable').each(() => {
      //   rescale($(this))
      // })
      rescale($('.scalable'));
    })
  </script> -->
</body>

</html>