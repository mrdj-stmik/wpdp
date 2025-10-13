<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h1 class="text-cintaraja">Dashboard KipApp WPDP</h1>
        <p class="text-muted">Selamat datang di sistem WPDP Bulanan Desa Cintaraja</p>
    </div>
</div>

<div class="row">

    <!-- Input Master -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-cintaraja text-white fw-bold">
                <i class="bi bi-folder-plus me-2"></i>Input Master
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="bi bi-diagram-3 me-2"></i><a href="<?= base_url('program'); ?>">Input Program</a></li>
                    <li class="list-group-item"><i class="bi bi-diagram-2-fill me-2"></i><a href="<?= base_url('kegiatan'); ?>">Input Kegiatan</a></li>
                    <li class="list-group-item"><i class="bi bi-boxes me-2"></i><a href="<?= base_url('kro'); ?>">Input KRO</a></li>
                    <li class="list-group-item"><i class="bi bi-list-task me-2"></i><a href="<?= base_url('ro'); ?>">Input RO</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Input WPDP Bulanan -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-cintaraja text-white fw-bold">
                <i class="bi bi-calendar-check me-2"></i>Input WPDP Bulanan
            </div>
            <div class="card-body">
                <p>Masukkan data realisasi WPDP setiap bulan sesuai program dan RO.</p>
                <a href="<?= base_url('wpdp'); ?>" class="btn btn-outline-warning"><i class="bi bi-pencil-square me-1"></i>Input WPDP</a>
            </div>
        </div>
    </div>

    <!-- Export Excel -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-cintaraja text-white fw-bold">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Data
            </div>
            <div class="card-body">
                <p>Unduh data WPDP Bulanan dalam format Excel untuk pelaporan atau arsip.</p>
                <a href="<?= base_url('export'); ?>" class="btn btn-outline-success"><i class="bi bi-download me-1"></i>Export Excel</a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>