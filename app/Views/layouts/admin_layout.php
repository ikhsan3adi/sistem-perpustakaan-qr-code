<!DOCTYPE html>
<html lang="en">

<head>
  <?= $this->include('layouts/head') ?>

  <!-- Extra head e.g title -->
  <?= $this->renderSection('head') ?>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar -->
    <?= $this->include('layouts/sidebar') ?>

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <?= $this->include('layouts/header') ?>

      <div class="container-fluid d-flex flex-wrap" style="min-height: 100vh;">
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
  <?= $this->include('imports/scripts/admin') ?>

  <!-- Extra scripts -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>