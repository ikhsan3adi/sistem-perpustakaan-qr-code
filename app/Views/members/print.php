<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('layouts/head') ?>

    <!-- Extra head e.g title -->
    <?= $this->renderSection('head') ?>

    <link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print User</title>
</head>

<body>
    <style>
        #img {
            background-image: url(<?= base_url('assets/images/image.png') ?>);
        }
    </style>
    <div class="w-50 m-5 card border border-5">

        <div id="img" class="d-flex justify-content-around ">
            <div class="p-3">
                <div class="text-center">
                    <img class="w-25" src="<?= base_url('assets/images/smea.png') ?>" alt="">
                    <h1 class="text-uppercase fw-bold ">Perpustakaan </h1>
                    <h6 class="text-uppercase">smkn 1 boyolangu</h6>
                    <img class="mt-2" id="qr-code" src="<?= base_url(MEMBERS_QR_CODE_URI . $member['qr_code']); ?>" alt="" style="max-width: 230px; height: 130px; object-fit: contain;">
                    <h3 class="mt-2 text-capitalize"><?= $member['first_name'] . ' ' . $member['last_name'] ?></h3>
                </div>

            </div>
            <div class="p-3">
                <h2 class="text-center  text-bg-dark p-2 mb-3"><?= $member['type'] ?></h2>
                <h4 class="text-center text-dark mb-4"><?= '240' . $member['id'] ?></h4>
                <div class="p-2"><img style="width: 200px; height: 250px; object-fit: cover; box-shadow: 2px 2px 4px 0px rgba(0, 0, 0, 0.3);
" src="<?= base_url(USER_PROFILE_URI . $member['profile_picture']) ?>" alt="">
                </div>

            </div>


        </div>
    </div>

</body>
<script>
    // window.print()
</script>

</html>