<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Konfirmasi Pengembalian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');

$loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
$loanDueDate = Time::parse($loan['due_date'], locale: 'id');

$isLate = $now->isAfter($loanDueDate);
$daysLate = $now->today()->difference($loanDueDate)->getDays();

?>

<a href="<?= base_url('admin/returns/new/search'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<form action="<?= base_url('admin/returns'); ?>" method="post">
  <?= csrf_field(); ?>
  <input type="hidden" name="loan_uid" value="<?= $loan['uid']; ?>">
  <input type="hidden" name="date" value="<?= Time::now(locale: 'id'); ?>">
  <!-- Loan -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-3">Data Peminjaman</h5>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <div class="row">
            <div class="col-12 mb-3">
              <label for="member_name" class="form-label">Nama peminjam</label>
              <input type="text" class="form-control" id="member_name" name="member_name" value="<?= "{$loan['first_name']} {$loan['last_name']}"; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="member_email" class="form-label">Email</label>
              <input type="text" class="form-control" id="member_email" name="member_email" value="<?= $loan['email']; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="member_phone" class="form-label">Nomor telepon</label>
              <input type="text" class="form-control" id="member_phone" name="member_phone" value="<?= $loan['phone']; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="member_address" class="form-label">Alamat</label>
              <input type="text" class="form-control" id="member_address" name="member_address" value="<?= $loan['address']; ?>" disabled>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <div class="row">
            <div class="col-12 mb-3">
              <label for="book_title" class="form-label">Judul buku</label>
              <input type="text" class="form-control" id="book_title" name="book_title" value="<?= "{$loan['title']} ({$loan['year']})"; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="book_author" class="form-label">Pengarang & Penerbit</label>
              <input type="text" class="form-control" id="book_author" name="book_author" value="<?= "{$loan['author']}; {$loan['publisher']}"; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="book_category" class="form-label">Kategori</label>
              <input type="text" class="form-control" id="book_category" name="book_category" value="<?= $loan['category']; ?>" disabled>
            </div>
            <div class="col-12 mb-3">
              <label for="quantity" class="form-label">Jumlah</label>
              <input type="text" class="form-control" id="quantity" name="quantity" value="<?= $loan['quantity']; ?>" disabled>
            </div>
          </div>
        </div>
        <hr>
        <div class="col-12">
          <div class="row">
            <div class="col-6 col-md-4 mb-3">
              <label for="loan_date" class="form-label">Tanggal pinjam</label>
              <input type="datetime" class="form-control" id="loan_date" name="loan_date" value="<?= $loan['loan_date']; ?>" disabled>
            </div>
            <div class="col-6 col-md-4 mb-3">
              <label for="loan_date" class="form-label">Tenggat pengembalian</label>
              <input type="datetime" class="form-control" id="loan_date" name="loan_date" value="<?= $loan['due_date']; ?>" disabled>
            </div>
            <div class="col-6 col-md-4 mb-3">
              <label for="loan_date" class="form-label">Terlambat</label>
              <input type="datetime" class="form-control" id="loan_date" name="loan_date" value="<?= $isLate ? abs($daysLate) . ' Hari' : '-'; ?>" disabled>
            </div>
          </div>
        </div>
        <?php if ($isLate) :
          $finePerDay = intval(getenv('amountFinesPerDay'));
          $totalFine = abs($daysLate)  * $loan['quantity'] * $finePerDay;
        ?>
          <h5 class="card-title fw-semibold my-3">Denda</h5>
          <div class="row">
            <div class="col-12 col-md-8 mb-4 mb-md-2">
              <p>Keterlambatan * Jumlah buku * Denda per hari per buku</p>
              <div class="row">
                <div class="col-4 d-flex">
                  <div>
                    <input type="number" class="form-control" value="<?= abs($daysLate); ?>" aria-describedby="daysLateInfo" disabled>
                    <div id="daysLateInfo" class="form-text">
                      Hari terlambat
                    </div>
                  </div>
                  <h3 class="ps-4">*</h3>
                </div>
                <div class="col-4 d-flex">
                  <div>
                    <input type="number" class="form-control" value="<?= $loan['quantity']; ?>" aria-describedby="bookQuantity" disabled>
                    <div id="bookQuantity" class="form-text">
                      Jumlah buku
                    </div>
                  </div>
                  <h3 class="ps-4">*</h3>
                </div>
                <div class="col-4">
                  <input type="number" class="form-control" value="<?= $finePerDay; ?>" aria-describedby="finePerDay" disabled>
                  <div id="finePerDay" class="form-text">
                    Denda per hari
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 px-md-5 d-flex">
              <div class="mx-md-auto">
                <h5>Total denda:</h5>
                <h2 class="text-danger">Rp<?= $totalFine; ?></h2>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <button type="submit" onclick="return confirm('Apakah anda yakin?')" class="btn btn-primary mt-3">Konfirmasi</button>
    </div>
  </div>
</form>

<?= $this->endSection() ?>