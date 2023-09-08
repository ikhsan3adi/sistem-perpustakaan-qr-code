<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');

if (empty($returns)) : ?>
  <h5 class="card-title fw-semibold my-4 text-danger">Pengembalian tidak ditemukan</h5>
  <p class="text-danger"><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title fw-semibold my-4">Hasil pencarian data pengembalian</h5>
  <table class="table table-hover table-striped">
    <thead class="table-light">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama peminjam</th>
        <th scope="col">Judul buku</th>
        <th scope="col" class="text-center">Jumlah</th>
        <th scope="col">Tgl kembali</th>
        <th scope="col">Denda dibayar</th>
        <th scope="col">Jumlah denda</th>
        <th scope="col" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <?php $i = 1 ?>
      <?php foreach ($returns as $key => $return) : ?>
        <tr>
          <th scope="row"><?= $i++; ?></th>
          <td>
            <b><?= $return['first_name'] . ' ' . $return['last_name']; ?></b>
          </td>
          <td>
            <p><b><?= "{$return['title']} ({$return['year']})"; ?></b></p>
            <p class="text-body"><?= "Author: {$return['author']}"; ?></p>
          </td>
          <td class="text-center"><?= $return['quantity']; ?></td>
          <td>
            <b><?= Time::parse($return['return_date'])->toLocalizedString('dd/MM/y'); ?></b><br>
            <b><?= Time::parse($return['return_date'])->toLocalizedString('HH:mm:ss'); ?></b>
          </td>
          <td>
            <h5>Rp<?= $return['amount_paid'] ?? 0; ?></h5>
            <?php if ($return['amount_paid'] >= $return['fine_amount']) : ?>
              <span class="badge bg-success rounded-3 fw-semibold">Lunas</span>
            <?php endif; ?>
          </td>
          <td>
            <h5>Rp<?= $return['fine_amount']; ?></h5>
          </td>
          <td style="width: 120px;" class="text-center">
            <?php if ($return['amount_paid'] >= $return['fine_amount']) : ?>
              <button class="btn btn-dark mb-2" disabled>
                <i class="ti ti-check"></i>
                Pilih
              </button>
            <?php else : ?>
              <a href="<?= base_url("admin/fines/pay/{$return['uid']}"); ?>" class="btn btn-primary mb-2">
                <i class="ti ti-check"></i>
                Pilih
              </a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>