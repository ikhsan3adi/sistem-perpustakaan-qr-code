<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Dashboard</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <!-- BOOKS -->
  <div class="col-lg-3 col-sm-6">
    <a href="<?= base_url('admin/books'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-book"></i>
          </h2>
          <h3>
            <?= count($books); ?> Buku
          </h3>
        </div>
      </div>
    </a>
  </div>
  <!-- BOOK STOCK -->
  <div class="col-lg-3 col-sm-6">
    <a href="<?= base_url('admin/books'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-database"></i>
          </h2>
          <h3>
            <?= $totalBookStock; ?> Stok Buku
          </h3>
        </div>
      </div>
    </a>
  </div>
  <!-- RACKS -->
  <div class="col-lg-3 col-6">
    <a href="<?= base_url('admin/racks'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-columns"></i>
          </h2>
          <h3>
            <?= count($racks); ?> Rak Buku
          </h3>
        </div>
      </div>
    </a>
  </div>
  <!-- CATEGORIES -->
  <div class="col-lg-3 col-6">
    <a href="<?= base_url('admin/categories'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-category-2"></i>
          </h2>
          <h3>
            <?= count($categories); ?> Kategori
          </h3>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="row">
  <!-- MEMBER -->
  <div class="col-sm-6">
    <a href="<?= base_url('admin/members'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-user"></i>
          </h2>
          <h3>
            <?= count($members); ?> Anggota
          </h3>
        </div>
      </div>
    </a>
  </div>
  <!-- LOANS -->
  <div class="col-sm-6">
    <a href="<?= base_url('admin/loans'); ?>">
      <div class="card">
        <div class="card-body">
          <h2>
            <i class="ti ti-arrows-exchange"></i>
          </h2>
          <h3>
            <?= count($loans); ?> Transaksi Peminjaman
          </h3>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- REPORT TODAY -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h3 class="card-title"><b>Laporan Hari Ini</b></h3>
        <?= $dateNow->toLocalizedString('d MMMM Y'); ?>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6 col-md-3">
            <h4 class="text-success"><b>Anggota Baru</b></h4>
            <h3><?= count($newMembersToday) ?></h3>
          </div>
          <div class="col-6 col-md-3">
            <h4 class="text-info"><b>Peminjaman</b></h4>
            <h3><?= count($newLoansToday) ?></h3>
          </div>
          <div class="col-6 col-md-3">
            <h4 class="text-info"><b>Pengembalian</b></h4>
            <h3><?= count($newBookReturnsToday) ?></h3>
          </div>
          <div class="col-6 col-md-3">
            <h4 class="text-danger"><b>Jatuh Tempo</b></h4>
            <h3><?= count($returnDueToday) ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- OVERVIEW CHART -->
  <div class="col-lg-8 d-flex align-items-strech">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Ikhtisar 7 hari terakhir</h5>
          </div>
        </div>
        <div id="chart"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <!-- FINE INCOME -->
      <!-- PENDAPATAN DENDA -->
      <div class="col-lg-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <h5 class="card-title mb-9 fw-semibold"> Total Pendapatan Denda </h5>
            <div class="row align-items-start">
              <div class="col-9">
                <h4 class="fw-semibold mb-3">Rp<?= $fineIncomeThisMonth['value'] ?? 0; ?></h4>
                <div class="d-flex align-items-center">
                  <div class="me-4">
                    <span class="fs-2"><?= $dateNow->toLocalizedString('MMMM Y'); ?></span>
                  </div>
                </div>
              </div>
              <div class="col-3">
                <div class="d-flex justify-content-end">
                  <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                    <i class="ti ti-currency-dollar fs-6"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <?php
              $thisMonth = $fineIncomeThisMonth['value'];
              $lastMonth = $fineIncomeLastMonth['value'];

              $percentage = (($thisMonth - $lastMonth == 0 || $lastMonth == 0)
                ? 0
                : round(($thisMonth - $lastMonth) / $lastMonth * 100));
              ?>
              <div class="d-flex align-items-center mt-3">
                <span class="me-1 rounded-circle <?= $percentage >= 0 ? 'bg-light-success' : 'bg-light-danger'; ?> round-20 d-flex align-items-center justify-content-center">
                  <i class="ti <?= $percentage >= 0 ? 'ti-arrow-up-left text-success' : 'ti-arrow-down-left text-danger'; ?>  "></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0">
                  <?= ($percentage >= 0 ? '+' : '') . $percentage; ?>%
                </p>
                <p class="fs-3 mb-0 text">dari bulan sebelumnya</p>
              </div>
            </div>
          </div>
          <div id="fine"></div>
        </div>
      </div>
      <!-- TOTAL ARREARS -->
      <!-- TOTAL TUNGGAKAN -->
      <div class="col-lg-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-9">
                <h5 class="card-title mb-9 fw-semibold"> Total Tunggakan </h5>
                <h4 class="fw-semibold mb-3">Rp<?= $totalArrears; ?></h4>
                <div class="d-flex align-items-center">
                  <div class="me-4">
                    <span class="fs-2">
                      <?= "{$oldestFineDate->toLocalizedString('d MMMM Y')} - {$dateNow->toLocalizedString('d MMMM Y')}"; ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-3">
                <div class="d-flex justify-content-end">
                  <div class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                    <i class="ti ti-currency-dollar fs-6"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3"></div>
          </div>
          <div id="arrears"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url("assets/libs/apexcharts/apexcharts.min.js") ?>"></script>
<script>
  $(function() {
    // =====================================
    // Overview
    // =====================================
    const newMembersData = [
      <?php foreach ($newMembersOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map((value => parseInt(value)));

    const loansData = [
      <?php foreach ($loansOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map((value => parseInt(value)));

    const returnsData = [
      <?php foreach ($returnsOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map((value => parseInt(value)));

    const highestValue = Math.max(
      Math.max(...newMembersData),
      Math.max(...loansData),
      Math.max(...returnsData)
    );

    var chart = {
      series: [{
          name: "Anggota baru",
          type: 'bar',
          data: newMembersData
        },
        {
          name: "Transaksi peminjaman",
          type: 'bar',
          data: loansData
        },
        {
          name: "Transaksi pengembalian",
          type: 'bar',
          data: returnsData
        },
      ],
      chart: {
        type: "bar",
        height: 400,
        offsetX: -15,
        toolbar: {
          show: true
        },
        foreColor: "#adb0bb",
        fontFamily: 'inherit',
        sparkline: {
          enabled: false
        },
      },
      plotOptions: {
        bar: {
          columnWidth: '80%',
          dataLabels: {
            position: 'top',
          }
        }
      },
      colors: ["#40dfb0", "#3849b9", "#db50c0"],
      markers: {
        size: 0
      },
      dataLabels: {
        enabled: false,
        offsetY: -17,
        style: {
          colors: ['#666666']
        },
      },
      legend: {
        show: true
      },
      grid: {
        borderColor: "rgba(0,0,0,0.1)",
        strokeDashArray: 3,
        xaxis: {
          lines: {
            show: false,
          },
        },
      },
      xaxis: {
        type: "category",
        categories: [
          <?php foreach ($lastWeekDateStringRange as $value) : ?>
            <?= "'{$value}', "; ?>
          <?php endforeach; ?>
        ],
        labels: {
          style: {
            cssClass: "fill-color"
          },
        },
      },
      yaxis: {
        show: true,
        min: 0,
        max: () => {
          const roundedHighestValue = (Math.ceil(highestValue / 10) * 10);

          if (roundedHighestValue <= 30) {
            return roundedHighestValue + 5;
          } else {
            return roundedHighestValue + 10;
          }
        },
        tickAmount: 5,
        labels: {
          style: {
            cssClass: "fill-color",
          },
        },
      },
      tooltip: {
        theme: "light"
      },
      responsive: [{
        breakpoint: 600,
        options: {
          plotOptions: {
            bar: {
              columnWidth: '100%',
            }
          },
          dataLabels: {
            enabled: false,
          },
        }
      }]
    };
    new ApexCharts(document.querySelector("#chart"), chart).render();

    // =====================================
    // FINES
    // =====================================
    var fines = {
      chart: {
        type: "area",
        height: 60,
        sparkline: {
          enabled: true,
        },
        group: "sparklines",
        fontFamily: "Plus Jakarta Sans', sans-serif",
        foreColor: "#49ca74",
      },
      series: [{
        name: "Denda terkumpul",
        color: "#49ca74",
        data: [<?= $fineIncomeLastMonth['value']; ?>, <?= $fineIncomeThisMonth['value']; ?>],
      }],
      xaxis: {
        type: "category",
        categories: ['<?= $fineIncomeLastMonth['month']; ?>', '<?= $fineIncomeThisMonth['month']; ?>'],
        labels: {
          style: {
            cssClass: "fill-color"
          },
        },
      },
      stroke: {
        curve: "smooth",
        width: 2,
      },
      fill: {
        colors: ["#f3feff"],
        type: "solid",
        opacity: 0.05,
      },
      markers: {
        size: 0,
      },
      tooltip: {
        theme: "dark",
        fixed: {
          enabled: true,
          position: "right",
        },
        x: {
          show: true,
        },
      },
    };
    new ApexCharts(document.querySelector("#fine"), fines).render();

    // =====================================
    // ARREARS
    // =====================================
    var arrears = {
      chart: {
        type: "area",
        height: 60,
        sparkline: {
          enabled: true,
        },
        group: "sparklines",
        fontFamily: "Plus Jakarta Sans', sans-serif",
        foreColor: "#ca495c",
      },
      series: [{
        name: "Total tunggakan (akumulasi)",
        color: "#ca495c",
        data: [
          <?php foreach ($arrears as $arrear) : ?>
            <?= "'{$arrear['arrear']}', "; ?>
          <?php endforeach; ?>
        ],
      }],
      xaxis: {
        type: "category",
        categories: [
          <?php foreach ($arrears as $arrear) : ?>
            <?= "'{$arrear['date']}', "; ?>
          <?php endforeach; ?>
        ],
        labels: {
          style: {
            cssClass: "fill-color"
          },
        },
      },
      stroke: {
        curve: "smooth",
        width: 2,
      },
      fill: {
        colors: ["#f3feff"],
        type: "solid",
        opacity: 0.05,
      },
      markers: {
        size: 0,
      },
      tooltip: {
        theme: "dark",
        fixed: {
          enabled: true,
          position: "right",
        },
        x: {
          show: true,
        },
      },
    };
    new ApexCharts(document.querySelector("#arrears"), arrears).render();
  })
</script>
<?= $this->endSection() ?>