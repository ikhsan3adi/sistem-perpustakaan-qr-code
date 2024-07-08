<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengaturan Denda</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

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
    <h5 class="card-title fw-semibold mb-4">Pengaturan Denda</h5>
    <form action="<?= base_url('admin/fines/settings/' . $fine['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PATCH">
      <div class="row">
        <div class="col-12 col-md-6">
          <label for="amount" class="form-label">Nilai denda per hari (Rp)</label>
          <div class="input-group">
            <input type="number" class="form-control <?php if ($validation->hasError('amount')) : ?>is-invalid<?php endif ?>" id="amount" name="amount" value="<?= $oldInput['amount'] ?? $fine['amount'] ?? ''; ?>" placeholder="1000" required>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          <?php if ($validation->hasError('amount')) : ?>
            <span class="text-danger small">
              <?= $validation->getError('amount'); ?>
            </span>
          <?php endif; ?>
          <div class="form-text mt-3">
            Minimal Rp1000.
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>