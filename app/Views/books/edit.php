<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Edit Buku</title>
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
    <h5 class="card-title fw-semibold">Edit Buku</h5>
    <form action="<?= base_url('admin/books/' . $book['slug']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 p-3">
          <label for="cover" class="d-block" style="cursor: pointer;">
            <div class="d-flex justify-content-center bg-light overflow-hidden h-100 position-relative">
              <?php
              $coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
              ?>
              <img id="bookCoverPreview" src="<?= base_url((!empty($book['book_cover']) && file_exists($coverImageFilePath)) ? $coverImageFilePath : BOOK_COVER_URI . DEFAULT_BOOK_COVER); ?>" alt="" height="300" class="z-1">
            </div>
          </label>
        </div>
        <div class="col-12 col-md-6 col-lg-8 col-xl-9">
          <div class="mb-3">
            <label for="cover" class="form-label">Gambar sampul buku</label>
            <input class="form-control <?php if ($validation->hasError('cover')) : ?>is-invalid<?php endif ?>" type="file" id="cover" name="cover" onchange="previewImage()">
            <div class="invalid-feedback">
              <?= $validation->getError('cover'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="title" class="form-label">Judul buku</label>
            <input type="text" class="form-control <?php if ($validation->hasError('title')) : ?>is-invalid<?php endif ?>" id="title" name="title" value="<?= $oldInput['title'] ?? $book['title']; ?>" required>
            <div class="invalid-feedback">
              <?= $validation->getError('title'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="author" class="form-label">Pengarang</label>
            <input type="text" class="form-control <?php if ($validation->hasError('author')) : ?>is-invalid<?php endif ?>" id="author" name="author" value="<?= $oldInput['author'] ?? $book['author']; ?>" required>
            <div class="invalid-feedback">
              <?= $validation->getError('author'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="publisher" class="form-label">Penerbit</label>
            <input type="text" class="form-control <?php if ($validation->hasError('publisher')) : ?>is-invalid<?php endif ?>" id="publisher" name="publisher" value="<?= $oldInput['publisher'] ?? $book['publisher']; ?>" required>
            <div class="invalid-feedback">
              <?= $validation->getError('publisher'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="isbn" class="form-label">ISBN</label>
          <input type="number" class="form-control <?php if ($validation->hasError('isbn')) : ?>is-invalid<?php endif ?>" id="isbn" name="isbn" minlength="10" maxlength="13" aria-describedby="isbnHelp" value="<?= $oldInput['isbn'] ?? $book['isbn']; ?>" required>
          <div id="isbnHelp" class="form-text">
            ISBN must be 10-13 characters long, contain only numbers.
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('isbn'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="year" class="form-label">Tahun terbit</label>
          <input type="number" class="form-control <?php if ($validation->hasError('year')) : ?>is-invalid<?php endif ?>" id="year" name="year" minlength="4" maxlength="4" value="<?= $oldInput['year'] ?? $book['year']; ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('year'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="rack" class="form-label">Rak</label>
          <select class="form-select <?php if ($validation->hasError('rack')) : ?>is-invalid<?php endif ?>" aria-label="Select rack" id="rack" name="rack" value="<?= $oldInput['rack'] ?? $book['rack_id']; ?>" required>
            <option>--Pilih rak--</option>
            <?php foreach ($racks as $rack) : ?>
              <option value="<?= $rack['id']; ?>" <?= ($oldInput['rack'] ?? $book['rack_id']) == $rack['id'] ? 'selected' : ''; ?>><?= $rack['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $validation->getError('rack'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="category" class="form-label">Kategori</label>
          <select class="form-select <?php if ($validation->hasError('category')) : ?>is-invalid<?php endif ?>" aria-label="Select category" id="category" name="category" value="<?= $oldInput['category'] ?? $book['category_id']; ?>" required>
            <option>--Pilih category--</option>
            <?php foreach ($categories as $category) : ?>
              <option value="<?= $category['id']; ?>" <?= ($oldInput['category'] ?? $book['category_id']) == $category['id'] ? 'selected' : ''; ?>><?= $category['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $validation->getError('category'); ?>
          </div>
        </div>
        <div class="col-12 col-lg-4 mb-3">
          <label for="stock" class="form-label">Jumlah stok buku</label>
          <input type="number" class="form-control <?php if ($validation->hasError('stock')) : ?>is-invalid<?php endif ?>" id="stock" name="stock" value="<?= $oldInput['stock'] ?? $book['quantity']; ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('stock'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function previewImage() {
    const fileInput = document.querySelector('#cover');
    const imagePreview = document.querySelector('#bookCoverPreview');

    const reader = new FileReader();
    reader.readAsDataURL(fileInput.files[0]);

    reader.onload = function(e) {
      imagePreview.src = e.target.result;
    };
  }
</script>
<?= $this->endSection() ?>