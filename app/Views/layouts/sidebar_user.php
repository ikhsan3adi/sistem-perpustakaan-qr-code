<?php

/**
 * List of sidebar navigations
 */
$sidebarNavs =
  [
    'Home',
    [
      'name' => 'Buku',
      'link' => '/',
      'icon' => 'ti ti-book'
    ],
    [
      'name' => 'Kategori',
      'link' => '/categories',
      'icon' => 'ti ti-category-2'
    ],
    'Transaksi',
    [
      'name' => 'Peminjaman',
      'link' => '/loans',
      'icon' => 'ti ti-arrows-exchange'
    ],
    [
      'name' => 'Pengembalian',
      'link' => '/returns',
      'icon' => 'ti ti-check'
    ],
    [
      'name' => 'Denda',
      'link' => '/fines',
      'icon' => 'ti ti-report-money'
    ],
    'Member',
    [
      'name' => 'Daftar Member',
      'link' => '/register-user',
      'icon' => 'ti ti-user'
    ],
  ];


$kategoriBook =
  [
    [
      'name' => 'Fiksi',
      'link' => '/?search=a',
    ],
    [
      'name' => 'Non-Fiksi',
      'link' => '/admin/users',
    ],
    [
      'name' => 'Sejarah',
      'link' => '/admin/users',
    ],
    [
      'name' => 'Komik',
      'link' => '/admin/users',
    ],
    [
      'name' => 'Teknologi',
      'link' => '/admin/users',
    ],
  ]


?>

<!-- Sidebar Start -->
<aside class="left-sidebar position-fixed">
  <!-- Sidebar scroll-->
  <div>
    <!-- Brand -->
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <div class="pt-4 mx-auto">
        <a href="<?= base_url(); ?>">
          <h2>Buku<span class="text-primary">Hub</span></h2>
        </a>
      </div>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <?php foreach ($sidebarNavs as $nav) : ?>
          <?php if (gettype($nav) === 'string') : ?>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><?= $nav; ?></span>
            </li>
          <?php else : ?>
            <?php if ($nav['name'] === 'Kategori') { ?>
              <li class="sidebar-item">
                <div class="d-flex">
                  <span class="sidebar-link ">
                    <i class="<?= $nav['icon']; ?>"></i>
                  </span>
                  <select class="hide-menu" style="border: none;">
                    <option value="" selected disabled>Pilih Kategori</option>
                    <?php foreach ($kategoriBook as $book) : ?>
                      <option value="<?= $book['link'] ?>"><?= $book['name']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
            <?php
            } else { ?>
              <li class="sidebar-item">
                <a class="sidebar-link" href="<?= base_url($nav['link']) ?>" aria-expanded="false">
                  <span>
                    <i class="<?= $nav['icon']; ?>"></i>
                  </span>
                  <span class="hide-menu"><?= $nav['name']; ?></span>
                </a>
              </li>
            <?php } ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->