<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Detail Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  #qr-code {
    background-image: url(<?= base_url(MEMBERS_QR_CODE_URI . $member['qr_code']); ?>);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    max-width: 400px;
    height: 400px;
  }
</style>
<?php
if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <h5 class="card-title fw-semibold mb-4">Detail Anggota</h5>
      <div class="d-flex gap-2 justify-content-end">
        <div>
          <a href="<?= base_url("admin/members/{$member['uid']}/edit"); ?>" class="btn btn-primary w-100">
            <i class="ti ti-edit"></i>
            Edit
          </a>
        </div>
        <div>
          <form action="<?= base_url("admin/members/{$member['uid']}"); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?');">
              <i class="ti ti-trash"></i>
              Delete
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-12 col-lg-7 d-flex flex-wrap">
        <div class="col-12">
          <div class="w-100 mb-4">
            <table>
              <tr>
                <td style="width:30%">
                  <h5><b>Nama Lengkap</b></h5>
                </td>
                <td style="width:15px">
                  <h5><b>:</b></h5>
                </td>
                <td>
                  <h5><b><?= $member['first_name'] . ' ' . $member['last_name']; ?></b></h5>
                </td>
              </tr>
              <tr>
                <td>
                  <h5>Email</h5>
                </td>
                <td>
                  <h5>:</h5>
                </td>
                <td>
                  <h5><?= $member['email']; ?></h5>
                </td>
              </tr>
              <tr>
                <td>
                  <h5>Nomor telepon</h5>
                </td>
                <td>
                  <h5>:</h5>
                </td>
                <td>
                  <h5><?= $member['phone']; ?></h5>
                </td>
              </tr>
              <tr>
                <td>
                  <h5>Alamat</h5>
                </td>
                <td>
                  <h5>:</h5>
                </td>
                <td>
                  <h5><?= $member['address']; ?></h5>
                </td>
              </tr>
              <tr>
                <td>
                  <h5>Tanggal lahir</h5>
                </td>
                <td>
                  <h5>:</h5>
                </td>
                <td>
                  <h5><?= $ciTime::parse($member['date_of_birth'])->toLocalizedString('d MMMM Y'); ?></h5>
                </td>
              </tr>
              <tr>
                <td>
                  <h5>Jenis kelamin</h5>
                </td>
                <td>
                  <h5>:</h5>
                </td>
                <td>
                  <h5><?= $member['gender'] == 'Male' ? 'Laki-laki' : 'Perempuan' ?></h5>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-book"></i>
                  </h2>
                  <h5>Buku dipinjam: </h5>
                  <h3>
                    <?= $totalBooksLent; ?>
                  </h3>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-arrows-exchange"></i>
                  </h2>
                  <h5>Transaksi peminjaman: </h5>
                  <h3>
                    <?= $loanCount; ?>
                  </h3>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-check"></i>
                  </h2>
                  <h5>Transaksi pengembalian: </h5>
                  <h3>
                    <?= $returnCount; ?>
                  </h3>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-calendar-time"></i>
                  </h2>
                  <h5>Jumlah terlambat: </h5>
                  <h3>
                    <?= $lateCount; ?>
                  </h3>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-report-money"></i>
                  </h2>
                  <h5>Denda belum dibayar: </h5>
                  <h3>
                    Rp<?= $unpaidFines; ?>
                  </h3>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card" style="height: 180px;">
                <div class="card-body">
                  <h2>
                    <i class="ti ti-cash"></i>
                  </h2>
                  <h5>Denda dibayar: </h5>
                  <h3>
                    Rp<?= $paidFines; ?>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-5">
        <div class="card">
          <div class="card-body">
            <div id="qr-code"></div>
          </div>
        </div>
        <h6 class="text-center">UID : <?= $member['uid']; ?></h6>
      </div>
    </div>
    <div class="row">
    </div>
  </div>
</div>
<?= $this->endSection() ?>