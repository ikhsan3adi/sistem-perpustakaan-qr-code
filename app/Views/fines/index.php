<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Denda</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Denda</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <input type="hidden" name="paid-off" value="<?= $paidOffFilter ? 'true' : 'false'; ?>">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari denda" aria-label="Cari denda" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-12 mb-3">
        <div class="d-flex gap-2">
          <p class="my-auto">Filter:</p>
          <div>
            <a href="<?= $paidOffFilter ? base_url('admin/fines?paid-off=false') : '#'; ?>" class="btn btn<?= $paidOffFilter ? '-outline' : ''; ?>-warning py-2">
              <?php if (!$paidOffFilter) : ?>
                <i class="ti ti-check"></i>
              <?php endif; ?>
              Belum lunas
            </a>
          </div>
          <div>
            <a href="<?= $paidOffFilter ? '#' : base_url('admin/fines?paid-off=true'); ?>" class="btn btn<?= $paidOffFilter ? '' : '-outline'; ?>-success py-2">
              <?php if ($paidOffFilter) : ?>
                <i class="ti ti-check"></i>
              <?php endif; ?>
              Lunas
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
          <th scope="col">Tgl pengembalian</th>
          <th scope="col">Denda dibayar</th>
          <th scope="col">Jumlah denda</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1 + ($itemPerPage * ($currentPage - 1));

        $now = Time::now(locale: 'id');
        ?>
        <?php if (empty($fines)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php
        foreach ($fines as $key => $fine) :
          $loanReturnDate = Time::parse($fine['return_date'], locale: 'id');
          $loanDueDate = Time::parse($fine['due_date'], locale: 'id');
        ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <a href="<?= base_url("admin/members/{$fine['member_uid']}"); ?>" class="text-decoration-underline">
                <p>
                  <b><?= "{$fine['first_name']} {$fine['last_name']}"; ?></b>
                </p>
              </a>
            </td>
            <td>
              <p><b><?= "{$fine['title']} ({$fine['year']})"; ?></b></p>
              <p class="text-body"><?= "Jumlah: {$fine['quantity']}"; ?></p>
            </td>
            <td class="text-danger-emphasis">
              <p><b><?= $loanReturnDate->toLocalizedString('dd/MM/y'); ?></b></p>
              <p class="text-body"><?= "Terlambat: " . abs($loanReturnDate->difference($loanDueDate)->getDays()) . " Hari"; ?></p>
            </td>
            <td>
              <h5>Rp<?= $fine['amount_paid'] ?? 0; ?></h5>
              <?php if ($paidOffFilter || $fine['amount_paid'] >= $fine['fine_amount']) : ?>
                <span class="badge bg-success rounded-3 fw-semibold">Lunas</span>
              <?php endif; ?>
            </td>
            <td>
              <h5>Rp<?= $fine['fine_amount']; ?></h5>
            </td>
            <td>
              <?php if (!$paidOffFilter && $fine['amount_paid'] < $fine['fine_amount']) : ?>
                <a href="<?= base_url("admin/fines/pay/{$fine['uid']}"); ?>" class="d-block btn btn-warning w-100 mb-2">
                  Bayar
                </a>
              <?php endif; ?>
              <a href="<?= base_url("admin/returns/{$fine['uid']}"); ?>" class="d-block btn btn-primary w-100 mb-2">
                Detail
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('fines', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>