<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Admin</title>
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
      <h5 class="card-title fw-semibold mb-4">Data Admin</h5>
      <div>
        <a href="<?= base_url('admin/users/new'); ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i>
          Tambah Admin Baru
        </a>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">Tanggal dibuat</th>
          <th scope="col" class="text-center">Group</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php foreach ($users as $user) : ?>
          <?php
          $userAttributes = $user->toArray();
          $userIdentities = $user->identities[0]->toArray();
          $userGroup = $user->getGroups()[0];
          ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $userAttributes['username']; ?></b>
            </td>
            <td>
              <b><?= $userIdentities['secret']; ?></b>
            </td>
            <td>
              <?= $userAttributes['created_at']; ?>
            </td>
            <td class="text-center">
              <?php if ($userGroup === 'superadmin') : ?>
                <span class="badge bg-success rounded-3 fw-semibold text-black"><?= $userGroup; ?></span>
              <?php elseif ($userGroup === 'admin') : ?>
                <span class="badge bg-primary rounded-3 fw-semibold"><?= $userGroup; ?></span>
              <?php else : ?>
                <span class="badge bg-black rounded-3 fw-semibold"><?= $userGroup; ?></span>
              <?php endif; ?>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("admin/users/{$userAttributes['id']}/edit"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-edit"></i>
                  Edit
                </a>
                <form action="<?= base_url("admin/users/{$userAttributes['id']}"); ?>" method="post">
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
    <?= $pager->links('users', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>