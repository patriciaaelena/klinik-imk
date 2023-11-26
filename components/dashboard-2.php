<?php
if (!isset($halaman)) {
  require_once('../404.php');
  die;
}
require_once('./function/PengajuanCuti.php');
$pengajuan = PengajuanCuti('GET-ONE-SIMPLE', ['status_pengajuan' => 'Proses', 'id_pegawai' => $user['id_pegawai']]);
if ($pengajuan === NULL) {
  $pengajuan = PengajuanCuti('GET-ONE-SIMPLE', ['status_pengajuan' => 'Disetujui', 'id_pegawai' => $user['id_pegawai']]);
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="info-box mb-3 bg-<?= $pengajuan === NULL ? "info" : ($pengajuan['status_pengajuan'] === "Proses" ? "warning" : "success") ?> justify-content-between">
        <div class="d-flex">
          <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
          <div class="info-box-content flex">
            <span class="info-box-text"><?= $pengajuan === NULL ? "Tidak Ada Pengajuan Cuti" : ($pengajuan['status_pengajuan'] === "Proses" ? "Sedang Proses Pengajuan" : "Pengajuan Cuti Disetujui, dari " . $fmt->format(strtotime($pengajuan['mulai_cuti'])) . " hingga " . $fmt->format(strtotime($pengajuan['selesai_cuti'])) . " ($pengajuan[lama_cuti] hari)") ?></span>
          </div>
        </div>
        <?php if ($pengajuan === NULL || $pengajuan['status_pengajuan'] !== "Disetujui") { ?>
          <div class="d-flex align-items-center pr-3">
            <a href="./cuti" class="btn btn-primary"><?= $pengajuan === NULL ? "Ajukan Cuti" : "Periksa" ?></a>
          </div>
        <?php } ?>
        <!-- /.info-box-content -->
      </div>
    </div>
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-6">
      <!-- Horizontal Form -->
      <div class="card card-info">
        <!-- form start -->
        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
          <div class="card-body">
            <div class="d-flex flex-column">
              <!-- <img src="http://kkn.upr.ac.id/upload/foto/203030503101.png" class="img-thumbnail" width="200px"> -->
              <div class="col d-flex flex-column">
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Nama</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nama_pegawai'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">NIK</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nik'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">NIP</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nip'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Jabatan</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nama_jabatan'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Unit Kerja</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['nama_unitkerja'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Masa Kerja</label>
                  <div class="col-9">
                    <?php
                    $date1 = new DateTime($user['mulai_kerja']);
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
                    <input type="text" class="form-control" readonly value="<?= implode(", ", $tgl) ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">No HP</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['no_hp'] ?>">
                  </div>
                </div>
                <div class="d-flex align-items-center form-group">
                  <label for="inputEmail3" class="form-label col-3">Status</label>
                  <div class="col-9">
                    <input type="text" class="form-control" readonly value="<?= $user['status'] ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <!-- <div class="card-footer">
            <button type="submit" class="btn btn-info float-right">Simpan</button>
          </div> -->
          <!-- /.card-footer -->
        </form>
      </div>
      <!-- /.card -->

    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Monthly Recap Report</h5>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <div class="btn-group">
              <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-wrench"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right" role="menu">
                <a href="#" class="dropdown-item">Action</a>
                <a href="#" class="dropdown-item">Another action</a>
                <a href="#" class="dropdown-item">Something else here</a>
                <a class="dropdown-divider"></a>
                <a href="#" class="dropdown-item">Separated link</a>
              </div>
            </div>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <p class="text-center">
                <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
              </p>

              <div class="chart">
                <!-- Sales Chart Canvas -->
                <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
              </div>
              <!-- /.chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <p class="text-center">
                <strong>Goal Completion</strong>
              </p>

              <div class="progress-group">
                Add Products to Cart
                <span class="float-right"><b>160</b>/200</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-primary" style="width: 80%"></div>
                </div>
              </div>
              <!-- /.progress-group -->

              <div class="progress-group">
                Complete Purchase
                <span class="float-right"><b>310</b>/400</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-danger" style="width: 75%"></div>
                </div>
              </div>

              <!-- /.progress-group -->
              <div class="progress-group">
                <span class="progress-text">Visit Premium Page</span>
                <span class="float-right"><b>480</b>/800</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-success" style="width: 60%"></div>
                </div>
              </div>

              <!-- /.progress-group -->
              <div class="progress-group">
                Send Inquiries
                <span class="float-right"><b>250</b>/500</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-warning" style="width: 50%"></div>
                </div>
              </div>
              <!-- /.progress-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- ./card-body -->
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                <h5 class="description-header">$35,210.43</h5>
                <span class="description-text">TOTAL REVENUE</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                <h5 class="description-header">$10,390.90</h5>
                <span class="description-text">TOTAL COST</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                <h5 class="description-header">$24,813.53</h5>
                <span class="description-text">TOTAL PROFIT</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block">
                <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                <h5 class="description-header">1200</h5>
                <span class="description-text">GOAL COMPLETIONS</span>
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-8">
      <!-- MAP & BOX PANE -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">US-Visitors Report</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="d-md-flex">
            <div class="p-1 flex-fill" style="overflow: hidden">
              <!-- Map will be created here -->
              <div id="world-map-markers" style="height: 325px; overflow: hidden">
                <div class="map"></div>
              </div>
            </div>
            <div class="card-pane-right bg-success pt-2 pb-2 pl-4 pr-4">
              <div class="description-block mb-4">
                <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                <h5 class="description-header">8390</h5>
                <span class="description-text">Visits</span>
              </div>
              <!-- /.description-block -->
              <div class="description-block mb-4">
                <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                <h5 class="description-header">30%</h5>
                <span class="description-text">Referrals</span>
              </div>
              <!-- /.description-block -->
              <div class="description-block">
                <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                <h5 class="description-header">70%</h5>
                <span class="description-text">Organic</span>
              </div>
              <!-- /.description-block -->
            </div><!-- /.card-pane-right -->
          </div><!-- /.d-md-flex -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <div class="row">
        <div class="col-md-6">
          <!-- DIRECT CHAT -->
          <div class="card direct-chat direct-chat-warning">
            <div class="card-header">
              <h3 class="card-title">Direct Chat</h3>

              <div class="card-tools">
                <span title="3 New Messages" class="badge badge-warning">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                  <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->

            <!-- /.card-body -->
            <div class="card-footer">
              <form action="#" method="post">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                  <span class="input-group-append">
                    <button type="button" class="btn btn-warning">Send</button>
                  </span>
                </div>
              </form>
            </div>
            <!-- /.card-footer-->
          </div>
          <!--/.direct-chat -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- TABLE: LATEST ORDERS -->
      <div class="card">
        <div class="card-header border-transparent">
          <h3 class="card-title">Latest Orders</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Item</th>
                  <th>Status</th>
                  <th>Popularity</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR9842</a></td>
                  <td>Call of Duty IV</td>
                  <td><span class="badge badge-success">Shipped</span></td>
                  <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR1848</a></td>
                  <td>Samsung Smart TV</td>
                  <td><span class="badge badge-warning">Pending</span></td>
                  <td>
                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR7429</a></td>
                  <td>iPhone 6 Plus</td>
                  <td><span class="badge badge-danger">Delivered</span></td>
                  <td>
                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR7429</a></td>
                  <td>Samsung Smart TV</td>
                  <td><span class="badge badge-info">Processing</span></td>
                  <td>
                    <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR1848</a></td>
                  <td>Samsung Smart TV</td>
                  <td><span class="badge badge-warning">Pending</span></td>
                  <td>
                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR7429</a></td>
                  <td>iPhone 6 Plus</td>
                  <td><span class="badge badge-danger">Delivered</span></td>
                  <td>
                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                  </td>
                </tr>
                <tr>
                  <td><a href="pages/examples/invoice.html">OR9842</a></td>
                  <td>Call of Duty IV</td>
                  <td><span class="badge badge-success">Shipped</span></td>
                  <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
          <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->

    <div class="col-md-4">
      <!-- Info Boxes Style 2 -->
      <div class="info-box mb-3 bg-warning">
        <span class="info-box-icon"><i class="fas fa-tag"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Inventory</span>
          <span class="info-box-number">5,200</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
      <div class="info-box mb-3 bg-success">
        <span class="info-box-icon"><i class="far fa-heart"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Mentions</span>
          <span class="info-box-number">92,050</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
      <div class="info-box mb-3 bg-danger">
        <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Downloads</span>
          <span class="info-box-number">114,381</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
      <div class="info-box mb-3 bg-info">
        <span class="info-box-icon"><i class="far fa-comment"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Direct Messages</span>
          <span class="info-box-number">163,921</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Browser Usage</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="chart-responsive">
                <canvas id="pieChart" height="150"></canvas>
              </div>
              <!-- ./chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <ul class="chart-legend clearfix">
                <li><i class="far fa-circle text-danger"></i> Chrome</li>
                <li><i class="far fa-circle text-success"></i> IE</li>
                <li><i class="far fa-circle text-warning"></i> FireFox</li>
                <li><i class="far fa-circle text-info"></i> Safari</li>
                <li><i class="far fa-circle text-primary"></i> Opera</li>
                <li><i class="far fa-circle text-secondary"></i> Navigator</li>
              </ul>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="card-footer p-0">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link">
                United States of America
                <span class="float-right text-danger">
                  <i class="fas fa-arrow-down text-sm"></i>
                  12%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                India
                <span class="float-right text-success">
                  <i class="fas fa-arrow-up text-sm"></i> 4%
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                China
                <span class="float-right text-warning">
                  <i class="fas fa-arrow-left text-sm"></i> 0%
                </span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Recently Added Products</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-footer text-center">
          <a href="javascript:void(0)" class="uppercase">View All Products</a>
        </div>
      </div>
    </div>
  </div>
</div>