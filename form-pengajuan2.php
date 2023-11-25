<?php


$halaman = "cetak";
require_once('function.php');
require_once('./function/PengajuanCuti.php');
if (!isset($_GET['id'])) {
  require_once('./404.php');
  die;
}
$pengajuan = PengajuanCuti('GET-ONE', $_GET);
if ($pengajuan == false) {
  require_once('./404.php');
  die;
}
require_once('./lib/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
// foreach ($pengajuan as $key => $value) {
//   var_dump($key, $value);
//   echo '<br>';
// }
$dompdf = new Dompdf();
$dompdf->loadHtml("<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset='UTF-8'>
  <title>Document</title>
  <!-- <link rel='stylesheet' href='./dist/css/adminlte.css'> -->
  <link rel='stylesheet' href='./dist/css/form.css'>
  <script src='./plugins/jquery/jquery.js'></script>
  <script src='./plugins/bootstrap/js/bootstrap.bundle.js'></script>
  <script src='./dist/js/adminlte.js'></script>
</head>

<body>
  <div class='main d-flex flex-column gap-3'>
    <header class='d-flex justify-content-end'>
      <div class='d-flex flex-column gap-3'>
        <div class='d-flex flex-column'>
          <div>ANAK LAMPIRAN 1.b</div>
          <div>PERATURAN BADAN KEPEGAWAIAN NEGARA</div>
          <div>REPUBLIK INDONESIA</div>
          <div>NOMOR 24 TAHUN 2017</div>
          <div>TENTANG</div>
          <div>TATA CARA PEMBERIAN CUTI PEGAWAI NEGARA SIPIL</div>
        </div>
        <table>
          <tr>
            <td class='no-padding'>Palangka Raya,</td>
            <td class='no-padding'>11 Oktober 2023</td>
          </tr>
          <tr>
            <td class='no-padding'></td>
            <td class='no-padding'>Kepada</td>
          </tr>
          <tr>
            <td class='no-padding text-center'>Yth.</td>
            <td class='no-padding'>Kepala Biro Umum dan Keuangan</td>
          </tr>
          <tr>
            <td class='no-padding'></td>
            <td class='no-padding'>Universitas Palangka Raya</td>
          </tr>
          <tr>
            <td class='no-padding'></td>
            <td class='no-padding'>di</td>
          </tr>
          <tr>
            <td class='no-padding'></td>
            <td class='no-padding'>PALANGKA RAYA</td>
          </tr>

        </table>
      </div>
    </header>
    <main class='d-flex flex-column gap-4'>
      <div class='col text-center'>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='4'>I. DATA PEGAWAI</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>Nama</td>
          <td class='px-only-3 border border-dark'>Jokoasdad asdadas asdadas asdasd</td>
          <td class='px-only-3 border border-dark'>NIP.</td>
          <td class='px-only-3 border border-dark'>199912121212120001</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>Jabatan</td>
          <td class='px-only-3 border border-dark'>Jabatan</td>
          <td class='px-only-3 border border-dark cell-shrink'>Masa Kerja</td>
          <td class='px-only-3 border border-dark'>Masa Kerja</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark cell-shrink'>Unit Kerja</td>
          <td class='px-only-3 border border-dark' colspan='3'>Unit Kerja</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='4'>II. JENIS CUTI YANG DIAMBIL **</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>1. Cuti Tahunan</td>
          <td class='px-only-3 border border-dark'>V</td>
          <td class='px-only-3 border border-dark'>2. Cuti Besar</td>
          <td class='px-only-3 border border-dark'>V</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>1. Cuti Tahunan</td>
          <td class='px-only-3 border border-dark'>V</td>
          <td class='px-only-3 border border-dark'>2. Cuti Besar</td>
          <td class='px-only-3 border border-dark'>V</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>1. Cuti Tahunan</td>
          <td class='px-only-3 border border-dark'>V</td>
          <td class='px-only-3 border border-dark'>2. Cuti Besar</td>
          <td class='px-only-3 border border-dark'>V</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='4'>III. ALASAN CUTI</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>ALASAN</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='6'>IV. LAMANYA CUTI</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark cell-shrink'>Selama</td>
          <td class='px-only-3 border border-dark'>1 hari</td>
          <td class='px-only-3 border border-dark cell-shrink'>mulai tanggal</td>
          <td class='px-only-3 border border-dark'>sekian2</td>
          <td class='px-only-3 border border-dark cell-shrink'>s/d</td>
          <td class='px-only-3 border border-dark'>sekian2</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='5'>V. CATATAN CUTI</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark' colspan='3'>1. CUTI TAHUNAN</td>
          <td class='px-only-3 border border-dark'>2. CUTI BESAR</td>
          <td class='px-only-3 border border-dark'>2. CUTI BESAR</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark cell-shrink'>Tahun</td>
          <td class='px-only-3 border border-dark cell-shrink'>Sisa</td>
          <td class='px-only-3 border border-dark'>Keterangan</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>2023</td>
          <td class='px-only-3 border border-dark'>6</td>
          <td class='px-only-3 border border-dark'>Keterangan</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>2023</td>
          <td class='px-only-3 border border-dark'>6</td>
          <td class='px-only-3 border border-dark'>Keterangan</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>2023</td>
          <td class='px-only-3 border border-dark'>6</td>
          <td class='px-only-3 border border-dark'>Keterangan</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
          <td class='px-only-3 border border-dark'>3. CUTI SAKIT</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='2'>VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
          <td class='px-only-3 border border-dark' colspan='2'>ALAMAT SELAMA MENJALANKAN CUTI</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'></td>
          <td class='px-only-3 border border-dark cell-shrink' colspan='2'>TELP. 0897678952312</td>
          <td class='px-only-3 border border-dark'></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark'></td>
          <td class='px-only-3 border-inline border-dark' colspan='3'>Hormat saya</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark'></td>
          <td class='px-only-3 border-inline border-dark' colspan='3'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark'></td>
          <td class='px-only-3 border-inline border-dark' colspan='3'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark'></td>
          <td class='px-only-3 border-inline border-dark' colspan='3'>Joko koaodkoasdj asdasd</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline-end border-dark'></td>
          <td class='px-only-3 border-inline-end border-dark' colspan='3'>NIP. 12312312323424</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='4'>VII. PERTIMBANGAN ATASAN LANGSUNG **</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>DISETUJUI</td>
          <td class='px-only-3 border border-dark'>PERUBAHAN ****</td>
          <td class='px-only-3 border border-dark'>DITANGGUHKAN ****</td>
          <td class='px-only-3 border border-dark'>TIDAK DI SETUJUI ****</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'>Hormat saya</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'>Joko koaodkoasdj asdasd</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline-end border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline-end border-dark' colspan='2'>NIP. 12312312323424</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='px-only-3 border border-dark' colspan='4'>VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI **</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'>DISETUJUI</td>
          <td class='px-only-3 border border-dark'>PERUBAHAN ****</td>
          <td class='px-only-3 border border-dark'>DITANGGUHKAN ****</td>
          <td class='px-only-3 border border-dark'>TIDAK DI SETUJUI ****</td>
        </tr>
        <tr>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
          <td class='px-only-3 border border-dark'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'>Hormat saya</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'><br></td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline border-dark' colspan='2'>Joko koaodkoasdj asdasd</td>
        </tr>
        <tr>
          <td class='px-only-3 border-inline-end border-dark' colspan='2'></td>
          <td class='px-only-3 border-inline-end border-dark' colspan='2'>NIP. 12312312323424</td>
        </tr>
      </table>
    </main>
    <footer>
      <table>
        <tr>
          <td class='px-only-3' colspan='2'>Catatan :</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
        <tr>
          <td class='px-only-3'>****</td>
          <td class='px-only-3'>diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
        </tr>
      </table>
      <div class='logo-footer d-flex align-items-center'>
        <img src='./assets/logo-blu.png' alt='' width='70px'>
      </div>
    </footer>
  </div>
</body>
</html>");

$dompdf->render();
$dompdf->stream('document', ['Attachment' => 0]);
