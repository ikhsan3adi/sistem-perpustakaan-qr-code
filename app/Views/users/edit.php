<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Data Admin</title>
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

<?php
$userId = $user->toArray()['id'];
$username = $user->toArray()['username'];
$email = $user->identities[0]->toArray()['secret'];
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Edit Data Admin</h5>
    <form action="<?= base_url('admin/users/' . $userId); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control <?php if ($validation->hasError('username')) : ?>is-invalid<?php endif ?>" id="username" name="username" value="<?= $oldInput['username'] ?? $username; ?>" placeholder="username" required>
            <div class="invalid-feedback">
              <?= $validation->getError('username'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control <?php if ($validation->hasError('email')) : ?>is-invalid<?php endif ?>" id="email" name="email" value="<?= $oldInput['email'] ?? $email; ?>" placeholder="email" required>
            <div class="invalid-feedback">
              <?= $validation->getError('email'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="password" class="form-label">Password baru</label>
            <input type="password" class="form-control <?php if ($validation->hasError('password')) : ?>is-invalid<?php endif ?>" id="password" name="password" value="<?= $oldInput['password'] ?? ''; ?>" placeholder="password">
            <div class="invalid-feedback">
              <?= $validation->getError('password'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="password_confirm" class="form-label">Konfirmasi password</label>
            <input type="password" class="form-control <?php if ($validation->hasError('password_confirm')) : ?>is-invalid<?php endif ?>" id="password_confirm" name="password_confirm" value="<?= $oldInput['password_confirm'] ?? ''; ?>" placeholder="confirm password">
            <div class="invalid-feedback">
              <?= $validation->getError('password_confirm'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>