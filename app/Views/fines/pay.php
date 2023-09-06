<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Bayar denda</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= previous_url(); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

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
    <div class="row">
      <div class="col-12 col-md-6">
        <h5 class="card-title fw-semibold mb-4">Detail Pengembalian</h5>
        <?php
        $returnData = [
          'Nama Lengkap'  => [$return['first_name'] . ' ' . $return['last_name']],
          'Email'         => $return['email'],
          'Judul buku'    => [$return['title']],
          'Jumlah pinjam' => $return['quantity'],
          'Tgl pinjam'    => $return['loan_date'],
          'Tenggat'       => $return['due_date'],
          'Tgl pengembalian' => $return['return_date'],
        ];
        ?>
        <!-- return data -->
        <div class="mb-4">
          <table>
            <?php foreach ($returnData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h5><b><?= $key; ?></b></h5>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h5><b>:</b></h5>
                  </td>
                  <td>
                    <h5><b><?= $value[0]; ?></b></h5>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h5><?= $key; ?></h5>
                  </td>
                  <td class="text-center">
                    <h5>:</h5>
                  </td>
                  <td>
                    <h5><?= $value; ?></h5>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <h5 class="card-title fw-semibold mb-4">Bayar denda</h5>
        <form action="<?= base_url("admin/fines/{$return['uid']}"); ?>" method="post">
          <?= csrf_field(); ?>
          <input type="hidden" name="_method" value="PUT">
          <div class="mb-3">
            <h5>Total denda : Rp<?= $return['fine_amount'] ?? 0; ?></h5>
            <h5>Dibayar : Rp<?= $return['amount_paid'] ?? 0; ?></h5>
            <h5>Sisa denda : Rp<?= ($return['fine_amount'] ?? 0) - ($return['amount_paid'] ?? 0); ?></h5>
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal bayar</label>
            <input type="number" class="form-control <?php if ($validation->hasError('nominal')) : ?>is-invalid<?php endif ?>" id="nominal" name="nominal" value="<?= $oldInput['nominal'] ?? ''; ?>" placeholder="sisa: <?= ($return['fine_amount'] ?? 0) - ($return['amount_paid'] ?? 0); ?>" min="1000" max="<?= ($return['fine_amount'] ?? 0) - ($return['amount_paid'] ?? 0); ?>" aria-describedby="nominalHelp" required>
            <div id="nominalHelp" class="form-text">
              Minimal Rp1000.
            </div>
            <div class="invalid-feedback">
              <?= $validation->getError('nominal'); ?>
            </div>
          </div>
          <button type="submit" class="btn btn-primary" onclick="return confirm('Konfirmasi');">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>