<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Rak Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
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
    <h5 class="card-title fw-semibold">Edit Rak</h5>
    <form action="<?= base_url('admin/racks/' . $rack['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="rack" class="form-label">Nama rak</label>
            <input type="text" class="form-control <?php if ($validation->hasError('rack')) : ?>is-invalid<?php endif ?>" id="rack" name="rack" value="<?= $oldInput['rack'] ?? $rack['name']; ?>" placeholder="'1A', 'A1'" required>
            <div class="invalid-feedback">
              <?= $validation->getError('rack'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="floor" class="form-label">Lantai</label>
            <input type="text" class="form-control <?php if ($validation->hasError('floor')) : ?>is-invalid<?php endif ?>" id="floor" name="floor" value="<?= $oldInput['floor'] ?? $rack['floor']; ?>" placeholder="Floor: 1">
            <div class="invalid-feedback">
              <?= $validation->getError('floor'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>