<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Edit Anggota</title>
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
    <h5 class="card-title fw-semibold">Edit Anggota</h5>
    <form action="<?= base_url('admin/members/' . $member['uid']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row mt-3">
        <div class="col-12 col-md-6 mb-3">
          <label for="first_name" class="form-label">Nama depan</label>
          <input type="text" class="form-control <?php if ($validation->hasError('first_name')) : ?>is-invalid<?php endif ?>" id="first_name" name="first_name" value="<?= $oldInput['first_name'] ?? $member['first_name'] ?? ''; ?>" placeholder="John Doe" required>
          <div class="invalid-feedback">
            <?= $validation->getError('first_name'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="last_name" class="form-label">Nama belakang</label>
          <input type="text" class="form-control <?php if ($validation->hasError('last_name')) : ?>is-invalid<?php endif ?>" id="last_name" name="last_name" value="<?= $oldInput['last_name'] ?? $member['last_name'] ?? ''; ?>">
          <div class="invalid-feedback">
            <?= $validation->getError('last_name'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control <?php if ($validation->hasError('email')) : ?>is-invalid<?php endif ?>" id="email" name="email" value="<?= $oldInput['email'] ?? $member['email'] ?? ''; ?>" placeholder="johndoe@gmail.com" required>
          <div class="invalid-feedback">
            <?= $validation->getError('email'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="phone" class="form-label">Nomor telepon</label>
          <input type="tel" class="form-control <?php if ($validation->hasError('phone')) : ?>is-invalid<?php endif ?>" id="phone" name="phone" value="<?= $oldInput['phone'] ?? $member['phone'] ?? ''; ?>" placeholder="+628912345" required>
          <div class="invalid-feedback">
            <?= $validation->getError('phone'); ?>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control <?php if ($validation->hasError('address')) : ?>is-invalid<?php endif ?>" id="address" name="address" required><?= $oldInput['address'] ?? $member['address'] ?? ''; ?></textarea>
        <div class="invalid-feedback">
          <?= $validation->getError('address'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="date_of_birth" class="form-label">Tanggal lahir</label>
          <input type="date" class="form-control <?php if ($validation->hasError('date_of_birth')) : ?>is-invalid<?php endif ?>" id="date_of_birth" name="date_of_birth" value="<?= $oldInput['date_of_birth'] ?? $member['date_of_birth'] ?? ''; ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('date_of_birth'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Jenis kelamin</label>
          <?php
          $gender = $oldInput['gender'] ?? $member['gender'] ?? '';
          ?>
          <div class="my-2 <?php if ($validation->hasError('gender')) : ?>is-invalid<?php endif ?>">
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" id="male" name="gender" value="1" <?= ($gender == '1' || $gender == 'Male') ? 'checked' : ''; ?> required>
              <label class="form-check-label" for="male">
                Laki-laki
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" id="female" name="gender" value="2" <?= ($gender == '2' || $gender == 'Female') ? 'checked' : ''; ?> required>
              <label class="form-check-label" for="female">
                Perempuan
              </label>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('gender'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>