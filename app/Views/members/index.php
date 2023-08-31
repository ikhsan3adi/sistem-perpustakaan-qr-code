<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Anggota</title>
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
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Anggota</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari anggota" aria-label="Cari anggota" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
          <div>
            <a href="<?= base_url('admin/members/new'); ?>" class="btn btn-primary py-2">
              <i class="ti ti-plus"></i>
              Tambah Anggota
            </a>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama lengkap</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Alamat</th>
          <th scope="col">Jenis kelamin</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($members)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($members as $key => $member) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <a href="<?= base_url("admin/members/{$member['uid']}"); ?>" class="text-primary-emphasis text-decoration-underline">
                <b><?= $member['first_name'] . ' ' . $member['last_name']; ?></b>
              </a>
            </td>
            <td><?= $member['email']; ?></td>
            <td><?= $member['phone']; ?></td>
            <td><?= $member['address']; ?></td>
            <td><?= $member['gender']; ?></td>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("admin/members/{$member['uid']}/edit"); ?>" class="btn btn-primary mb-2">
                  Edit
                </a>
                <form action="<?= base_url("admin/members/{$member['uid']}"); ?>" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('members', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>