<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Detail Peminjaman</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  #qr-code {
    background-image: url(<?= base_url(LOANS_QR_CODE_URI . $loan['loan_qr_code']); ?>);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    max-width: 500px;
    height: 300px;
  }
</style>
<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');
$loanDate = Time::parse($loan['loan_date'], locale: 'id');
$dueDate = Time::parse($loan['due_date'], locale: 'id');

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
    <div class="d-flex justify-content-between mb-4">
      <h5 class="card-title fw-semibold mb-4">Detail Peminjaman</h5>
      <div class="d-flex gap-2 justify-content-end gap-2">
        <form action="<?= base_url("admin/loans/{$loan['uid']}"); ?>" method="post">
          <?= csrf_field(); ?>
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" class="btn btn-danger mb-2" onclick="return confirm('Are you sure?');">
            <i class="ti ti-x"></i>
            Batalkan
          </button>
        </form>
        <div>
          <a href="" class="btn btn-primary w-100">
            <i class="ti ti-check"></i>
            Selesaikan pengembalian
          </a>
        </div>
      </div>
    </div>
    <?php
    $memberData = [
      'Nama Lengkap'  => [$loan['first_name'] . ' ' . $loan['last_name']],
      'Email'         => $loan['email'],
      'Nomor telepon' => $loan['phone'],
      'Alamat'        => $loan['address'],
    ];

    $bookData = [
      'Judul buku'    => [$loan['title']],
      'Pengarang'     => $loan['author'],
      'Penerbit'      => $loan['publisher']
    ];
    ?>
    <div class="row mb-3">
      <!-- member data -->
      <div class="col-12 col-md-6 d-flex flex-wrap">
        <div class="mb-4">
          <table>
            <?php foreach ($memberData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h5><b><?= $key; ?></b></h5>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h5><b>:</b></h5>
                  </td>
                  <td>
                    <h5><b><?= $value[0]; ?></b></h5>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h5><?= $key; ?></h5>
                  </td>
                  <td class="text-center">
                    <h5>:</h5>
                  </td>
                  <td>
                    <h5><?= $value; ?></h5>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <!-- book data -->
      <div class="col-12 col-md-6 d-flex flex-wrap">
        <div class="mb-4">
          <table>
            <?php foreach ($bookData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h5><b><?= $key; ?></b></h5>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h5><b>:</b></h5>
                  </td>
                  <td>
                    <h5><b><?= $value[0]; ?></b></h5>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h5><?= $key; ?></h5>
                  </td>
                  <td class="text-center">
                    <h5>:</h5>
                  </td>
                  <td>
                    <h5><?= $value; ?></h5>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="row">
          <!-- quantity -->
          <div class="col-12 col-sm-6 col-xl-4">
            <div class="card" style="height: 180px;">
              <div class="card-body">
                <h2>
                  <i class="ti ti-book"></i>
                </h2>
                <h5>Buku dipinjam: </h5>
                <h3>
                  <?= $loan['quantity']; ?>
                </h3>
              </div>
            </div>
          </div>
          <!-- status -->
          <div class="col-12 col-sm-6 col-xl-4">
            <div class="card" style="height: 180px;">
              <div class="card-body">
                <h2>
                  <?php if ($now->isBefore($dueDate)) : $status = 'Normal'; ?>
                    <i class="ti ti-clock-check"></i>
                  <?php elseif ($now->today()->equals($dueDate)) : $status = 'Jatuh tempo'; ?>
                    <i class="ti ti-clock-exclamation"></i>
                  <?php else : $status = 'Terlambat'; ?>
                    <i class="ti ti-clock-exclamation"></i>
                  <?php endif; ?>
                </h2>
                <h5>Status: </h5>
                <h3>
                  <?= $status; ?>
                </h3>
              </div>
            </div>
          </div>
          <!-- deadline -->
          <div class="col-12 col-sm-6 col-xl-4">
            <div class="card" style="height: 180px;">
              <div class="card-body">
                <h2>
                  <i class="ti ti-calendar-due"></i>
                </h2>
                <h5>Deadline: </h5>
                <h3>
                  <?= $now->difference($dueDate)->getDays(); ?> Hari lagi
                </h3>
              </div>
            </div>
          </div>
          <!-- loan date -->
          <div class="col-12 col-sm-6">
            <div class="card" style="height: 180px;">
              <div class="card-body">
                <h2>
                  <i class="ti ti-calendar-check"></i>
                </h2>
                <h5>Waktu pinjam: </h5>
                <h3>
                  <div><?= $loanDate->toLocalizedString('d MMMM y'); ?></div>
                  <?= $loanDate->toLocalizedString('HH:mm:ss'); ?>
                </h3>
              </div>
            </div>
          </div>
          <!-- due date -->
          <div class="col-12 col-sm-6">
            <div class="card" style="height: 180px;">
              <div class="card-body">
                <h2>
                  <i class="ti ti-calendar-due"></i>
                </h2>
                <h5>Batas waktu pengembalian: </h5>
                <h3>
                  <?= $dueDate->toLocalizedString('d MMMM y'); ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- qr code -->
      <div class="col-12 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div id="qr-code" class="m-auto d-flex">
              <?php if (!file_exists(LOANS_QR_CODE_PATH . $loan['qr_code']) || empty($loan['qr_code'])) : ?>
                <div class="m-auto">
                  <a href="<?= base_url("admin/loans/{$loan['uid']}?update-qr-code=true"); ?>" class="btn btn-outline-primary">
                    Generate QR Code
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <h6 class="text-center">UID : <?= $loan['uid']; ?></h6>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>