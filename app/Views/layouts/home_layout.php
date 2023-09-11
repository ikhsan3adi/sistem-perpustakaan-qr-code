<!DOCTYPE html>
<html lang="en">

<head>
  <?= $this->include('layouts/head') ?>

  <!-- Extra head e.g title -->
  <?= $this->renderSection('head') ?>

  <link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">
</head>

<body class="position-relative">
  <!--  Body Wrapper -->
  <div class="background">
  </div>

  <div class="page-wrapper" id="main-wrapper">
    <!--  Main wrapper -->
    <div class="body-wrapper position-relative">
      <?= $this->renderSection('back') ?>
      <div class="container col-xxl-8 px-4 py-5" style="min-height: 100vh;">
        <!-- Main content -->
        <div class="w-100">
          <?= $this->renderSection('content') ?>
        </div>

        <div class="align-self-end w-100">
          <?= $this->include('layouts/footer') ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <?= $this->include('imports/scripts/basic_scripts') ?>

  <!-- Extra scripts -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>