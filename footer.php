<?php
if (!isset($halaman)) {
    header("Location: 404.php");
    exit();
}
?>
<footer class="main-footer no-print">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $(document).ready(() => {
        $("#tabel1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        <?php if (isset($_SESSION['smess'])) { ?>
            Toast.fire({
                icon: "<?= $_SESSION['smess']['alert'] ?>",
                title: "<?= $_SESSION['smess']['msg'] ?>",
            })
        <?php } ?>
    })
</script>
</body>

</html>
<?php unset($_SESSION['smess']); ?>