<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penulis Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
    <div class="d-flex justify-content-between mb-2">
      <h5 class="card-title fw-semibold mb-4">Data Penulis</h5>
      <div>
        <a href="<?= base_url('admin/authors/new'); ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i>
          Tambah Penulis
        </a>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama Penulis</th>
          <th scope="col" class="text-center">Tahun</th>
          <th scope="col" class="text-center">Jumlah Buku</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php foreach ($authors as $key => $author) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <a href="<?= base_url("admin/authors/{$author['id']}"); ?>" class="text-primary-emphasis text-decoration-underline">
                <b><?= $author['name']; ?></b>
              </a>
            </td>
            <td class="text-center">
              <?= $author['year']; ?>
            </td>
            <td class="text-center">
              <?= $bookCountInAuthors[$key]; ?>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("admin/authors/{$author['id']}/edit"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-edit"></i>
                  Edit
                </a>
                <form action="<?= base_url("admin/authors/{$author['id']}"); ?>" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                    <i class="ti ti-trash"></i>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('authors', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>