<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

use CodeIgniter\I18n\Time;

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
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Pengembalian</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari data pengembalian" aria-label="Cari data pengembalian" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
          <div>
            <a href="<?= base_url('admin/returns/new/search'); ?>" class="btn btn-primary py-2">
              <i class="ti ti-plus"></i>
              Pengembalian baru
            </a>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama peminjam</th>
          <th scope="col">Judul buku</th>
          <th scope="col" class="text-center">Jumlah</th>
          <th scope="col">Tgl pinjam</th>
          <th scope="col">Tenggat</th>
          <th scope="col">Tgl pengembalian</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1 + ($itemPerPage * ($currentPage - 1));

        $now = Time::now(locale: 'id');
        ?>
        <?php if (empty($loans)) : ?>
          <tr>
            <td class="text-center" colspan="9"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php
        foreach ($loans as $key => $loan) :
          $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
          $loanDueDate = Time::parse($loan['due_date'], locale: 'id');
          $loanReturnDate = Time::parse($loan['return_date'], locale: 'id');

          $isFined = $loan['fine_id'] != null;
          $isFinePaid = $isFined ? (($loan['amount_paid'] ?? 0) >= $loan['fine_amount']) : true;

          $isLate = $now->isAfter($loanDueDate);
        ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <a href="<?= base_url("admin/members/{$loan['member_uid']}"); ?>" class="text-primary-emphasis text-decoration-underline">
                <p>
                  <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
                </p>
              </a>
            </td>
            <td>
              <a href="<?= base_url("admin/books/{$loan['slug']}"); ?>">
                <p class="text-primary-emphasis text-decoration-underline"><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
                <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
              </a>
            </td>
            <td class="text-center"><?= $loan['quantity']; ?></td>
            <td>
              <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
              <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
            </td>
            <td>
              <b><?= $loanDueDate->toLocalizedString('dd/MM/y'); ?></b>
            </td>
            <td class="<?= $isLate ? 'text-danger-emphasis' : ''; ?>">
              <b><?= $loanReturnDate->toLocalizedString('dd/MM/y'); ?></b><br>
              <b><?= $loanReturnDate->toLocalizedString('HH:mm:ss'); ?></b>
            </td>
            <td class="text-center">
              <?php if ($isFinePaid) : ?>
                <span class="badge bg-success rounded-3 fw-semibold"><?= $isFined ? 'Lunas' : 'Selesai'; ?></span>
              <?php else : ?>
                <span class="badge bg-danger rounded-3 fw-semibold">Menunggak</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="<?= base_url("admin/returns/{$loan['uid']}"); ?>" class="d-block btn btn-primary w-100 mb-2">
                Detail
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('returns', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>