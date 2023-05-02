<?php
$halaman = "Dashboard";
include "header.php";
$data = dashboard();
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if ($user['id'] == 1) { ?>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box h-100 d-flex flex-column bg-info">
                            <div class="inner row flex-grow-1">
                                <div class="col-3">
                                    <h3><?= $data['admin'] ?></h3>
                                </div>
                                <div class="col-9">
                                    <p>Pengguna<br>(termasuk admin)</p>
                                </div>
                            </div>
                            <!-- <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> -->
                            <a href="pengguna.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                <?php } ?>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box h-100 d-flex flex-column bg-success">
                        <div class="inner row flex-grow-1">
                            <div class="col-4">
                                <h3><?= $data['obat'] ?></h3>
                            </div>
                            <div class="col-8">
                                <p>Data Obat</p>
                            </div>
                        </div>
                        <!-- <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div> -->
                        <a href="obat.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box h-100 d-flex flex-column bg-danger">
                        <div class="inner row flex-grow-1">
                            <div class="col-4">
                                <h3><?= $data['masuk']['jumlah'] ?></h3>
                            </div>
                            <div class="col-8">
                                <p>Total Obat Masuk<br>bulan ini</p>
                            </div>
                        </div>
                        <a href="obat-masuk.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box h-100 d-flex flex-column bg-warning">
                        <div class="inner row flex-grow-1">
                            <div class="col-4">
                                <h3><?= $data['keluar']['jumlah'] ?></h3>

                                <h3><?= $data['keluar']['kedaluwarsa'] ?></h3>
                            </div>
                            <div class="col-8">
                                <p>Total<br>Obat Keluar</p>

                                <p>Kedaluwarsa</p>
                            </div>
                            <center class="col-12">bulan ini</center>
                        </div>
                        <!-- <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div> -->
                        <a href="obat-keluar.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- <br>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Title</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            Start creating your amazing application!
                        </div>
                        <div class="card-footer">
                            Footer
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
</div>
<?php
include "footer.php";
?>