<?php if (empty($members)) : ?>
  <h5 class="card-title fw-semibold my-4 text-danger">Anggota tidak ditemukan</h5>
  <p class="text-danger"><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title fw-semibold my-4">Hasil pencarian anggota</h5>
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
      <?php $i = 1 ?>
      <?php foreach ($members as $key => $member) : ?>
        <?php if (!$member['deleted_at']) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $member['first_name'] . ' ' . $member['last_name']; ?></b>
            </td>
            <td><?= $member['email']; ?></td>
            <td><?= $member['phone']; ?></td>
            <td><?= $member['address']; ?></td>
            <td><?= $member['gender']; ?></td>
            <td style="width: 120px;" class="text-center">
              <a href="<?= base_url("admin/loans/new/books/search?member-uid={$member['uid']}"); ?>" class="btn btn-primary mb-2">
                <i class="ti ti-check"></i>
                Pilih
              </a>
            </td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>