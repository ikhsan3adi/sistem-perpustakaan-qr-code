<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('layouts/head') ?>

    <!-- Extra head e.g title -->
    <?= $this->renderSection('head') ?>

    <link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class=" m-5 card border border-5">
        <div class="card-body ">
            <h5 class="card-title fw-semibold mb-4">Kartu TandaAnggota</h5>
            <div class="row mb-3">
                <div class="col-12 d-flex flex-wrap">
                    <div class="col-12">
                        <div class="w-100 mb-4">
                            <div class="d-flex ">
                                <div class="p-3">
                                    <img style="width: 200px; height: 250px; object-fit: cover;" src="<?= base_url(USER_PROFILE_URI . $member['profile_picture']) ?>" alt="">
                                </div>
                                <?php
                                $tableData =
                                    [
                                        'Nomor'         => '240' . $member['id'],
                                        'Nama'  => [$member['first_name'] . ' ' . $member['last_name']],
                                        'Jenis'         => $member['email'],
                                        'Alamat'        => $member['address'],
                                    ];
                                ?>
                                <div class="m-5">
                                    <table>
                                        <?php foreach ($tableData as $key => $value) : ?>
                                            <?php if (is_array($value)) : ?>
                                                <tr>
                                                    <td>
                                                        <h5><?= $key; ?></h5>
                                                    </td>
                                                    <td style="width:15px" class="text-center">
                                                        <h5><b>:</b></h5>
                                                    </td>
                                                    <td>
                                                        <h5><?= $value[0]; ?></h5>
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
                                    <div class="card-body ">
                                        <p class="text-center mb-4" style="line-break: anywhere;">UID : <?= $member['uid']; ?></p>
                                        <img id="qr-code" src="<?= base_url(MEMBERS_QR_CODE_URI . $member['qr_code']); ?>" alt="" style="max-width: 200px; height: 100px; object-fit: contain;">

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>
<script>
    window.print()
</script>

</html>