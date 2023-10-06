<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Tempat Terbit Buku</title>
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
    <h5 class="card-title fw-semibold">Edit Tempat Terbit</h5>
    <form action="<?= base_url('admin/places/' . $place['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12">
          <div class="my-3">
            <label for="place" class="form-label">Nama penerbit</label>
            <input type="text" class="form-control <?php if ($validation->hasError('place')) : ?>is-invalid<?php endif ?>" id="place" name="place" value="<?= $oldInput['place'] ?? $place['name']; ?>" placeholder="Tempat Terbit" required>
            <div class="invalid-feedback">
              <?= $validation->getError('place'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>