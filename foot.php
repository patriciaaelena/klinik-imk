<?php
if (!isset($halaman)) {
  require_once('404.php');
  die;
}
?>
</div>
<footer class="main-footer">
  <strong>Copyright &copy; 2023 <a href="">SISTEM INFORMASI CUTI</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 1.1.0
  </div>
</footer>
</div>
<script src="./plugins/datatables/jquery.dataTables.js"></script>
<script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="./plugins/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="./plugins/datatables-responsive/js/responsive.bootstrap4.js"></script>
<script src="./plugins/flatpickr/flatpickr.js"></script>
<script src="./plugins/flatpickr/id.js"></script>

<script src="./dist/js/demo.js"></script>
<script src="./dist/js/adminlte.js"></script>
<script src="./dist/js/pages/dashboard2.js"></script>
<script src="./plugins/sweetalert2/sweetalert2.js"></script>
<script>
  // eventlistener
  const ajaxCutiDetail = () => {
    $.post('', {
      id_jeniscuti: $('#id_jeniscuti').val(),
      ajax_cek_cuti: '',
    }, (res) => {
      $('#detail-cuti').html(res);
    });
  }
  $(document).ready(() => {
    <?php
    $dateNew = isset($date) ? $date : new DateTime();
    ?>
    $("#mulai_cuti") && $("#mulai_cuti").flatpickr({
      altInput: true,
      altFormat: "d/m/Y",
      dateFormat: "Y-m-d",
      minDate: "<?= $dateNew->format('Y-m-d') ?>",
      disable: [
        function(date) {
          return (date.getDay() === 0 || date.getDay() === 6); // disable weekends
        }
      ],
      locale: 'id',
    });
    if ($('#id_jeniscuti').val()) ajaxCutiDetail();
  });
  $('#id_jeniscuti').change(() => {
    ajaxCutiDetail();
  });
  // form validation
  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
  })();
  // ------------->
  // tooltip, select2, datatables
  $(function() {
    $('[data-toggle="tooltip"]').tooltip({
      html: true
    });
    $('.select2').select2();
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    $("#main-table") && $("#main-table").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: true,
    });
  });
  // ------------->
  // function
  const handleLogout = () => {
    Swal.fire({
      title: "Yakin logout?",
      showCancelButton: true,
      confirmButtonText: "Ya",
      canselButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $('#logout-form').submit();
      }
    });
  }
  // ------------->
  <?php if (isset($_SESSION['flash'])) { ?>
    // swal
    Swal.fire({
      icon: "<?= $_SESSION['flash']['status'] ?>",
      text: "<?= $_SESSION['flash']['msg'] ?>",
      timer: 3000
    });
    // ------------->
  <?php } ?>
  <?php if (isset($template)) { ?>
    <?php if ($template['id_tamplate'] === NULL || $template['nama_pertama'] === NULL || $template['nama_kedua'] === NULL) { ?>
      // swal
      Swal.fire({
        icon: "error",
        title: "Template persetujuan belum tersedia",
        text: "Template persetujuan cuti belum tersedia untuk jabatan anda. Silahkan hubungi admin terkait!",
        timer: 3000,
      }).then(() => {
        window.location.replace("./")
      });
      // ------------->
    <?php } ?>
  <?php } ?>
  <?php if (isset($displayAlert)) { ?>
    <?php if ($displayAlert) { ?>
      // swal
      Swal.fire({
        icon: "info",
        title: "Masih dalam masa cuti!",
        text: "Tidak dapat mengajukan cuti selama masa cuti belum selesai.",
        timer: 3000,
      }).then(() => {
        window.location.replace("./")
      });
      // ------------->
    <?php } ?>
  <?php } ?>
</script>
</body>

</html>
<?php
unset($_SESSION['flash']);
?>