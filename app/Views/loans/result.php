<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/loans'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

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
    <h5 class="card-title fw-semibold mb-4">Peminjaman Buku Berhasil</h5>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama peminjam</th>
          <th scope="col">Judul buku</th>
          <th scope="col" class="text-center">Jumlah</th>
          <th scope="col">Tgl pinjam</th>
          <th scope="col">Tgl pengembalian</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1;
        foreach ($newLoans as $loan) : ?>
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
            <td class="text-center"><b><?= $loan['quantity']; ?></b></td>
            <td><b><?= Time::parse($loan['loan_date'])->toLocalizedString('d/M/y'); ?></b></td>
            <td>
              <b><?= Time::parse($loan['due_date'])->toLocalizedString('d/M/y'); ?></b>
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("admin/loans/{$loan['uid']}"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-eye"></i>
                  Detail
                </a>
                <form action="<?= base_url("admin/loans/{$loan['uid']}"); ?>" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger mb-2" onclick="return confirm('Are you sure?');">
                    <i class="ti ti-x"></i>
                    Batalkan
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>