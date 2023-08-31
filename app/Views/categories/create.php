<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Kategori</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/categories'); ?>" class="btn btn-outline-primary mb-3">
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
    <h5 class="card-title fw-semibold">Tambah Kategori</h5>
    <form action="<?= base_url('admin/categories'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="my-3">
        <label for="category" class="form-label">Nama kategori</label>
        <input type="text" class="form-control <?php if ($validation->hasError('category')) : ?>is-invalid<?php endif ?>" id="category" name="category" value="<?= $oldInput['category'] ?? ''; ?>" required>
        <div class="invalid-feedback">
          <?= $validation->getError('category'); ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>