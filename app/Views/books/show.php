<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Detail Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
$ebookFilePath = EBOOK_URI . $book['file_att'];
?>
<style>
  #book-cover {
    background-image: url(<?= base_url((!empty($book['book_cover']) && file_exists($coverImageFilePath)) ? $coverImageFilePath : BOOK_COVER_URI . DEFAULT_BOOK_COVER); ?>);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    max-width: 400px;
    height: 380px;
  }
</style>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <a href="<?= base_url('admin/books'); ?>" class="btn btn-outline-primary">
          <i class="ti ti-arrow-left"></i>
          Kembali
        </a>
      </div>
      <div class="d-flex gap-2 justify-content-end">
        <div>
          <a href="<?= base_url("admin/books/{$book['slug']}/edit"); ?>" class="btn btn-primary w-100">
            <i class="ti ti-edit"></i>
            Edit
          </a>
        </div>
        <div>
          <form action="<?= base_url("admin/books/{$book['slug']}"); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?');">
              <i class="ti ti-trash"></i>
              Delete
            </button>
          </form>
        </div>
      </div>
    </div>
    <h5 class="card-title fw-semibold mb-4">Detail Buku</h5>
    <div class="row">
      <div class="col-12 col-lg-4">
        <div id="book-cover" class="mb-4 bg-light">
        </div>
      </div>
      <div class="col-12 col-lg-8 d-flex flex-wrap">
        <div class="w-100 mb-2">
          <h2 class="mb-4"><?= $book['title']; ?></h2>
          <?php if ($book['edition']) : ?>
            <h5>Edisi: <?= $book['edition']; ?></h5>
          <?php endif; ?>
          <h5>Bahasa: <?= $book['language_id'] ?? '-'; ?></h5>
          <h5>Jumlah Halaman: <?= $book['collation'] ?? '-'; ?></h5>
          <h5>Call Number: <?= $book['call_number'] ?? '-'; ?></h5>
          <h5>Tahun: <?= $book['year'] ?? '-'; ?></h5>
          <h5>Penulis: <?= $book['author'] ?? '-'; ?></h5>
          <h5>Penerbit: <?= $book['publisher'] ?? '-'; ?></h5>
          <h5>Tempat Terbit: <?= $book['place'] ?? '-'; ?></h5>
          <?php if ((!empty($book['file_att']) && file_exists($ebookFilePath))) : ?>
            <h5 class="mb-3">E-book: Ya</h5>
            <a href="<?= base_url($ebookFilePath); ?>" target="_blank" class="btn btn-primary mt-4">
              Lihat/Download file E-book
            </a>
          <?php else : ?>
            <h5>E-book: Tidak</h5>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h2>
          <i class="ti ti-database"></i>
        </h2>
        <h3>
          Total: <?= $book['quantity']; ?>
        </h3>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h2>
          <i class="ti ti-arrows-exchange"></i>
        </h2>
        <h3>
          Dipinjam: <?= $loanCount; ?>
        </h3>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h2>
          <i class="ti ti-book-2"></i>
        </h2>
        <h3>
          Tersedia: <?= $bookStock; ?>
        </h3>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>