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
    <h5 class="card-title fw-semibold mb-4">Daftar Buku</h5>
    <div class="row">
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
              <a href="javascript:void(0)">
                <div class="card-img-top rounded-0" id="coverBook<?= $book['id']; ?>">
                </div>
              </a>
            </div>
            <div class="card-body pt-3 p-4">
              <h6 class="fw-semibold fs-4"><?= "{$book['title']} ({$book['year']})"; ?></h6>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?= $pager->links('books', 'my_pager'); ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>