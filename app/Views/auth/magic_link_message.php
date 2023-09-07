<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= lang('Auth.useMagicLink') ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container d-flex justify-content-center p-5">
  <div class="card col-12 col-md-5 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-5"><?= lang('Auth.useMagicLink') ?></h5>

      <p><b><?= lang('Auth.checkYourEmail') ?></b></p>

      <p><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
    </div>
  </div>
</div>

<?= $this->endSection() ?>