<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Buku</title>
<?= $this->endSection() ?>

<?= $this->section('back'); ?>
<a href="<?= base_url(); ?>" class="btn btn-outline-primary m-3 position-absolute">
  <i class="ti ti-home"></i>
  Home
</a>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-body">
    <div class="row mb-4">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold">Daftar Buku</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari buku" aria-label="Cari buku" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <?php if (empty($books)) : ?>
        <h4 class="text-center">Buku tidak ditemukan</h4>
      <?php endif; ?>
      <?php foreach ($books as $book) : ?>
        <?php
        $coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
        ?>
        <style>
          #coverBook<?= $book['id']; ?> {
            background-image: url('<?= base_url((!empty($book['book_cover']) && file_exists($coverImageFilePath)) ? $coverImageFilePath : BOOK_COVER_URI . DEFAULT_BOOK_COVER); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top;
            height: 250px;
          }
        </style>
        <div class="col-sm-6 col-xl-3">
          <div class="card overflow-hidden rounded-2" style="height: 375px;">
            <div class="position-relative">
              <a href="<?= base_url("admin/books/{$book['slug']}"); ?>">
                <div class="card-img-top rounded-0" id="coverBook<?= $book['id']; ?>">
                </div>
              </a>
            </div>
            <div class="card-body pt-3 p-4">
              <h6 class="fw-semibold fs-4">
                <?= substr($book['title'], 0, 64) . ((strlen($book['title']) > 64) ? '...'  : '') . " ({$book['year']})"; ?>
              </h6>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?= $pager->links('books', 'my_pager'); ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>