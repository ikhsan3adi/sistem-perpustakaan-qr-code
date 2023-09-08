<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');

if (empty($loans)) : ?>
  <h5 class="card-title fw-semibold my-4 text-danger">Peminjaman tidak ditemukan</h5>
  <p class="text-danger"><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title fw-semibold my-4">Hasil pencarian data peminjaman</h5>
  <table class="table table-hover table-striped">
    <thead class="table-light">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama lengkap</th>
        <th scope="col">Judul buku</th>
        <th scope="col" class="text-center">Jumlah</th>
        <th scope="col">Tgl pinjam</th>
        <th scope="col">Tenggat</th>
        <th scope="col" class="text-center">Status</th>
        <th scope="col" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <?php $i = 1 ?>
      <?php foreach ($loans as $key => $loan) :
        $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
        $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

        $isLate = $now->isAfter($loanDueDate);
        $isDueDate = $now->today()->difference($loanDueDate)->getDays() == 0;

        if ($now->isBefore($loanDueDate)) {
          $status = 'Normal';
        } else if ($now->today()->equals($loanDueDate)) {
          $status = 'Jatuh tempo';
        } else {
          $status = 'Terlambat';
        }
      ?>
        <?php if (!$loan['deleted_at']) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $loan['first_name'] . ' ' . $loan['last_name']; ?></b>
            </td>
            <td>
              <p><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
              <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
            </td>
            <td class="text-center"><?= $loan['quantity']; ?></td>
            <td>
              <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
              <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
            </td>
            <td>
              <b><?= $loanDueDate->toLocalizedString('dd/MM/y'); ?></b>
            </td>
            <td class="text-center">
              <?php if ($now->isBefore($loanDueDate)) : ?>
                <span class="badge bg-success rounded-3 fw-semibold">Normal</span>
              <?php elseif ($now->today()->equals($loanDueDate)) : ?>
                <span class="badge bg-warning rounded-3 fw-semibold">Jatuh tempo</span>
              <?php else : ?>
                <span class="badge bg-danger rounded-3 fw-semibold">Terlambat</span>
              <?php endif; ?>
            </td>
            <td style="width: 120px;" class="text-center">
              <a href="<?= base_url("admin/returns/new?loan-uid={$loan['uid']}"); ?>" class="btn btn-primary mb-2">
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