<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Detail Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
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
    <div class="d-flex justify-content-between m-4 mb-4">
        <div>
            <a href="<?= base_url('/'); ?>" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <div>
                <a href="<?= base_url("/loans/member/search"); ?>" class="btn btn-primary w-100">
                    <i class="ti ti-plus"></i>
                    Pinjam
                </a>
            </div>
            <div>
                <button class="btn btn-success  w-100" onclick="getUlasan('<?= $book['slug']; ?>')"> <i class="ti ti-star"></i>
                    Ulasan</button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Detail Buku</h5>
        <div class="row">
            <div class="col-12 col-lg-4">
                <div id="book-cover" class="mb-4 bg-light">
                </div>
            </div>
            <div class="col-12 col-lg-8 d-flex flex-wrap">
                <div class="w-100 mb-2">
                    <h2 class="mb-2"><?= $book['title']; ?></h2>
                    <h5>Tahun: <?= $book['year']; ?></h5>
                    <h5>Pengarang: <?= $book['author']; ?></h5>
                    <h5>Penerbit: <?= $book['publisher']; ?></h5>
                    <h5>Kategori: <?= $book['category']; ?></h5>
                    <h5>Rak: <?= $book['rack']; ?>, Lantai <?= $book['floor']; ?></h5>
                    <h5>Tentang Buku: <?= $book['category']; ?></h5>
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
<script>
    function getUlasan(param) {
        // console.log(param);

        jQuery.ajax({
            url:"<?= base_url('books/'); ?>" + param, // Mengarahkan ke URL yang sesuai untuk mengambil ulasan buku
            type: 'get',
            data: {
                'param': param // Mengirim parameter slug buku
            },
            success: function(response, status, xhr) {
                $('#bookResult').html(response); // Memperbarui elemen HTML dengan ulasan yang diterima
                $('html, body').animate({
                    scrollTop: $("#bookResult").offset().top // Menggeser halaman ke elemen dengan id 'bookResult'
                }, 500);
            },
            error: function(xhr, status, thrown) {
                console.log(thrown);
                $('#bookResult').html(thrown); // Menampilkan pesan kesalahan jika terjadi kesalahan
            }
        });
    }
</script>

<?= $this->endSection() ?>
<?= $this->section('ulasan') ?>
<aside class="position-absolute top-0 end-0 p-3" style="width: 17.5%; background-color: white; ">
    <div class="my-4">
        <h5 class="card-title fw-semibold mb-4">Buku dipilih</h5>
        <ul id="bookList" class="d-flex d-flex flex-wrap gap-2">
            <li id="none">--Silahkan cari dan pilih buku terlebih dahulu--</li>
        </ul>
        <form id="bookForm" action="<?= base_url('loans/books/new'); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="member_uid" value="">
        </form>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="bookResult">
                <p class="text-center mt-4">Data buku muncul disini</p>
            </div>
        </div>
    </div>
</aside>

<?= $this->endSection() ?>