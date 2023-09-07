<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Home</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="px-4 pt-5 my-5 text-center border-bottom">
  <h1 class="display-4 fw-bold text-body-emphasis">Buku<span class="text-primary">Hub</span></h1>
  <div class="col-lg-6 mx-auto">
    <p class="lead mb-4">Temukan buku-buku menarik untuk memperluas pengetahuan dan imajinasi Anda. BukuHub adalah teman setia pencinta buku dan pembelajar di mana saja, kapan saja.</p>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
      <a href="<?= base_url('login'); ?>" class="btn btn-primary btn-lg px-4 me-sm-3">Login petugas</a>
      <a href="<?= base_url('book'); ?>" class="btn btn-outline-secondary btn-lg px-4">Daftar buku</a>
    </div>
  </div>
  <div class="overflow-hidden" style="max-height: 45vh;">
    <div class="container px-5">
      <img src="<?= base_url('assets/images/dashboard.png'); ?>" class="img-fluid border rounded-3 shadow-lg mb-4" alt="Example image" width="700" height="500" loading="lazy">
    </div>
  </div>
</div>
<?= $this->endSection() ?>