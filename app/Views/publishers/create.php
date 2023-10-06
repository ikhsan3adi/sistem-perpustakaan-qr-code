<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Penerbit Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/publishers'); ?>" class="btn btn-outline-primary mb-3">
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
    <h5 class="card-title fw-semibold">Tambah Penerbit</h5>
    <form action="<?= base_url('admin/publishers'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-12">
          <div class="my-3">
            <label for="publisher" class="form-label">Nama penerbit</label>
            <input type="text" class="form-control <?php if ($validation->hasError('publisher')) : ?>is-invalid<?php endif ?>" id="publisher" name="publisher" value="<?= $oldInput['publisher'] ?? ''; ?>" placeholder="Penerbit" required>
            <div class="invalid-feedback">
              <?= $validation->getError('publisher'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>