<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Penulis Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/authors'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Tambah Penulis</h5>
    <form action="<?= base_url('admin/authors'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="author" class="form-label">Nama penulis</label>
            <input type="text" class="form-control <?php if ($validation->hasError('author')) : ?>is-invalid<?php endif ?>" id="author" name="author" value="<?= $oldInput['author'] ?? ''; ?>" placeholder="Penulis" required>
            <div class="invalid-feedback">
              <?= $validation->getError('author'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="year" class="form-label">Tahun</label>
            <input type="number" class="form-control <?php if ($validation->hasError('year')) : ?>is-invalid<?php endif ?>" id="year" name="year" minlength="4" maxlength="4" value="<?= $oldInput['year'] ?? ''; ?>" placeholder="1999">
            <div class="invalid-feedback">
              <?= $validation->getError('year'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>