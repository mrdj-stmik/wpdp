<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="e-WPDP - Electronic Work Plan Disbursement Plan - BPS Kabupaten Tasikmalaya">
    <meta name="author" content="MRDJ - BPS Kabupaten Tasikmalaya">
    <meta name="keywords" content="WPDP, Work Plan, Disbursement Plan, BPS, Tasikmalaya, Perencanaan, Pencairan, Anggaran, Statistik">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'e-WPDP | BPS Kabupaten Tasikmalaya'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <?= $this->renderSection('styles') ?>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex: 1;
        }
        .bg-cintaraja {
            background-color: #fe6d00; /* oranye */
        }
        .text-cintaraja {
            color: #f97316;
        }
        footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            margin-top: auto;
            width: 100%;
        }
        footer .fw-semibold {
            font-weight: 600;
        }
        footer .badge {
            font-size: 0.7em;
        }
        .logo-bps {
            height: 40px;
            margin-right: 10px;
        }
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            text-align: center;
        }
        #loader .spinner-border {
            margin-top: 20%;
        }
    </style>
</head>
<body>

    <?= $this->include('layouts/navbar'); ?>

    <div id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <?php $session = \Config\Services::session(); ?>
        <?php if ($session->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $session->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($session->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $session->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($session->getFlashdata('errors')): ?>
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                 <ul>
                 <?php foreach ($session->getFlashdata('errors') as $error): ?>
                     <li><?= $error ?></li>
                 <?php endforeach; ?>
                 </ul>
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>
         <?php endif; ?>

        <?= $this->renderSection('content'); ?>
    </div>

    <?= $this->include('layouts/footer'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                document.getElementById('loader').style.display = 'block';
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>