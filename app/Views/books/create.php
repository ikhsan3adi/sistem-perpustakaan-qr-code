<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/books'); ?>" class="btn btn-outline-primary mb-3">
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
    <h5 class="card-title fw-semibold">Form Tambah Buku</h5>
    <form action="<?= base_url('admin/books'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 p-3">
          <label for="cover" class="d-block" style="cursor: pointer;">
            <div class="d-flex justify-content-center bg-light overflow-hidden h-100 position-relative">
              <img id="bookCoverPreview" src="<?= base_url(BOOK_COVER_URI . DEFAULT_BOOK_COVER); ?>" alt="" height="300" class="z-1">
              <p class="position-absolute top-50 start-50 translate-middle z-0">Pilih sampul</p>
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
            <input type="text" class="form-control <?php if ($validation->hasError('title')) : ?>is-invalid<?php endif ?>" id="title" name="title" value="<?= $oldInput['title'] ?? ''; ?>" required>
            <div class="invalid-feedback">
              <?= $validation->getError('title'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="edition" class="form-label">Edisi</label>
            <input type="text" class="form-control <?php if ($validation->hasError('edition')) : ?>is-invalid<?php endif ?>" id="edition" name="edition" value="<?= $oldInput['edition'] ?? ''; ?>">
            <div class="invalid-feedback">
              <?= $validation->getError('edition'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="author" class="form-label">Penulis</label>
            <div class="row">
              <div class="col-4">
                <input list="authorList" type="text" class="form-control <?php if ($validation->hasError('author')) : ?>is-invalid<?php endif ?>" id="author" name="author" value="<?= $oldInput['author'] ?? ''; ?>" placeholder="Pilih penulis" onchange="setAuthorName(this.value)">
              </div>
              <div class="col-8">
                <input type="text" class="form-control" id="authorName" name="authorName" value="<?= $oldInput['authorName'] ?? ''; ?>" readonly>
              </div>
            </div>
            <div class="invalid-feedback">
              <?= $validation->getError('author'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="publisher" class="form-label">Penerbit</label>
          <div class="row">
            <div class="col-4">
              <input list="publisherList" type="text" class="form-control <?php if ($validation->hasError('publisher')) : ?>is-invalid<?php endif ?>" id="publisher" name="publisher" value="<?= $oldInput['publisher'] ?? ''; ?>" placeholder="Pilih penerbit" onchange="setPublisherName(this.value)">
            </div>
            <div class="col-8">
              <input type="text" class="form-control" id="publisherName" name="publisherName" value="<?= $oldInput['authorName'] ?? ''; ?>" readonly>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('publisher'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="place" class="form-label">Tempat Terbit</label>
          <div class="row">
            <div class="col-4">
              <input list="placeList" type="text" class="form-control <?php if ($validation->hasError('place')) : ?>is-invalid<?php endif ?>" id="place" name="place" value="<?= $oldInput['place'] ?? ''; ?>" placeholder="Pilih tempat terbit" onchange="setPlaceName(this.value)">
            </div>
            <div class="col-8">
              <input type="text" class="form-control" id="placeName" name="placeName" value="<?= $oldInput['placeName'] ?? ''; ?>" readonly>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('place'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="year" class="form-label">Tahun terbit</label>
          <input type="number" class="form-control <?php if ($validation->hasError('year')) : ?>is-invalid<?php endif ?>" id="year" name="year" minlength="4" maxlength="4" value="<?= $oldInput['year'] ?? ''; ?>">
          <div class="invalid-feedback">
            <?= $validation->getError('year'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="collation" class="form-label">Jumlah Halaman</label>
          <input type="text" class="form-control <?php if ($validation->hasError('collation')) : ?>is-invalid<?php endif ?>" id="collation" name="collation" value="<?= $oldInput['collation'] ?? ''; ?>">
          <div class="invalid-feedback">
            <?= $validation->getError('collation'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="call_number" class="form-label">call number</label>
          <input type="text" class="form-control <?php if ($validation->hasError('call_number')) : ?>is-invalid<?php endif ?>" id="call_number" name="call_number" value="<?= $oldInput['call_number'] ?? ''; ?>">
          <div class="invalid-feedback">
            <?= $validation->getError('call_number'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="language" class="form-label">Bahasa</label>
          <select class="form-select <?php if ($validation->hasError('language')) : ?>is-invalid<?php endif ?>" aria-label="Select language" id="language" name="language" value="<?= $oldInput['language'] ?? ''; ?>">
            <?php
            $languages = [
              ['id' => 'en', 'name' => 'en: English'],
              ['id' => 'id', 'name' => 'id: Bahasa Indonesia']
            ];
            ?>
            <?php foreach ($languages as $language) : ?>
              <option value="<?= $language['id']; ?>" <?= ($oldInput['language'] ?? '') == $language['id'] ? 'selected' : ''; ?>><?= $language['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $validation->getError('language'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="isbn" class="form-label">ISBN</label>
          <input type="number" class="form-control <?php if ($validation->hasError('isbn')) : ?>is-invalid<?php endif ?>" id="isbn" name="isbn" minlength="10" maxlength="13" aria-describedby="isbnHelp" value="<?= $oldInput['isbn'] ?? ''; ?>">
          <div id="isbnHelp" class="form-text">
            ISBN must be 10-13 characters long, contain only numbers.
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('isbn'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="stock" class="form-label">Jumlah stok buku</label>
          <input type="number" class="form-control <?php if ($validation->hasError('stock')) : ?>is-invalid<?php endif ?>" id="stock" name="stock" value="<?= $oldInput['stock'] ?? ''; ?>">
          <div class="invalid-feedback">
            <?= $validation->getError('stock'); ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 mb-4">
        <label for="file_att" class="form-label">File E-book atau PDF (opsional)</label>
        <input class="form-control <?php if ($validation->hasError('file_att')) : ?>is-invalid<?php endif ?>" type="file" id="file_att" name="file_att" aria-describedby="pdf">
        <div id="pdf" class="form-text">
          PDF, DOCX, TXT format.
        </div>
        <div class="invalid-feedback">
          <?= $validation->getError('file_att'); ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <datalist id="authorList">
        <option>--Pilih penulis--</option>
        <?php foreach ($authors as $author) : ?>
          <option value="<?= $author['id']; ?>" <?= ($oldInput['author'] ?? '') == $author['id'] ? 'selected' : ''; ?>><?= $author['name']; ?></option>
        <?php endforeach; ?>
      </datalist>
      <datalist id="publisherList">
        <option>--Pilih penerbit--</option>
        <?php foreach ($publishers as $publisher) : ?>
          <option value="<?= $publisher['id']; ?>" <?= ($oldInput['publisher'] ?? '') == $publisher['id'] ? 'selected' : ''; ?>><?= $publisher['name']; ?></option>
        <?php endforeach; ?>
      </datalist>
      <datalist id="placeList">
        <option>--Pilih tempat terbit--</option>
        <?php foreach ($places as $place) : ?>
          <option value="<?= $place['id']; ?>" <?= ($oldInput['place'] ?? '') == $place['id'] ? 'selected' : ''; ?>><?= $place['name']; ?></option>
        <?php endforeach; ?>
      </datalist>
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

  const authorList = {
    <?php foreach ($authors as $author) : ?>
      <?= "\"{$author['id']}\""; ?>: <?= "\"{$author['name']}\","; ?>
    <?php endforeach; ?>
  };

  const publisherList = {
    <?php foreach ($publishers as $publisher) : ?>
      <?= "\"{$publisher['id']}\""; ?>: <?= "\"{$publisher['name']}\","; ?>
    <?php endforeach; ?>
  };

  const placeList = {
    <?php foreach ($places as $place) : ?>
      <?= "\"{$place['id']}\""; ?>: <?= "\"{$place['name']}\","; ?>
    <?php endforeach; ?>
  };

  function setAuthorName(text) {
    document.querySelector('#authorName').value = authorList[text] ?? '';
  }

  function setPublisherName(text) {
    document.querySelector('#publisherName').value = publisherList[text] ?? '';
  }

  function setPlaceName(text) {
    document.querySelector('#placeName').value = placeList[text] ?? '';
  }
</script>
<?= $this->endSection() ?>