<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja">
    <i class="bi bi-info-circle me-2"></i>Tentang e-WPDP BPS KAB. TASIKMALAYA 2025
</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <p class="fs-5 mb-4">
            <strong>e-WPDP</strong> (Electronic Work Plan Disbursement Plan) adalah aplikasi internal yang dikembangkan untuk mendukung pencatatan dan pelaporan kegiatan statistik secara efisien, transparan, dan terstruktur di lingkungan Badan Pusat Statistik Kab. Tasikmalaya.
        </p>

        <h5 class="text-cintaraja">
            <i class="bi bi-bullseye me-2"></i>Tujuan Aplikasi
        </h5>
        <ul>
            <li>Mempermudah pelaporan pelaksanaan kegiatan WPDP secara bulanan dan triwulanan</li>
            <li>Menstandarkan alur input, validasi, dan pelaporan WPDP di lingkungan BPS Kab. Tasikmalaya</li>
            <li>Mendukung penyusunan dokumen RPD dan FRA dengan data yang akurat dan terdokumentasi</li>
        </ul>

        <h5 class="text-cintaraja mt-4">
            <i class="bi bi-stars me-2"></i>Fitur Utama
        </h5>
        <ul>
            <li>Input dan rekap WPDP Bulanan</li>
            <li>Rekapitulasi Triwulanan otomatis</li>
            <li>Export ke Excel untuk keperluan pelaporan resmi</li>
            <li>Manajemen master data Program, Kegiatan, KRO, dan RO</li>
        </ul>

        <h5 class="text-cintaraja mt-4">
            <i class="bi bi-diagram-3 me-2"></i>Struktur Data
        </h5>
        <p>
            Sistem menggunakan struktur hirarki yang terdiri dari: <br>
            <strong>Program → Kegiatan → KRO (Komponen Rincian Output) → RO (Rincian Output)</strong> <br>
            Setiap rincian output dapat memiliki beberapa detil yang lebih spesifik, dan setiap detil dapat memiliki beberapa akun yang terkait.
        </p>

        <h5 class="text-cintaraja mt-4">
            <i class="bi bi-lock me-2"></i>Keamanan & Hak Akses
        </h5>
        <p>
            Hanya pengguna internal dengan hak akses tertentu yang dapat melakukan input dan pengelolaan data. Setiap aktivitas pengguna tercatat dalam sistem.
        </p>

        <h5 class="text-cintaraja mt-4">
            <i class="bi bi-people me-2"></i>Dikembangkan Oleh
        </h5>
        <p>
            Badan Pusat Statistik Kabupaten Tasikmalaya
        </p>

        <h5 class="text-cintaraja mt-4">
            <i class="bi bi-envelope me-2"></i>Kontak Pengelola
        </h5>
        <ul class="mb-0">
            <li>Email: <a href="mailto:admin_wpdp">admin@wpdp3206.web.bps.go.id</a></li>
            <li>Telepon: (0265) 123456</li>
        </ul>

        <hr class="my-4">
        <p class="text-muted mb-0">
            Versi Aplikasi: <strong>1.0</strong> &mdash; Update Terakhir: <strong>April 2025</strong>
        </p>
    </div>
</div>

<?= $this->endSection(); ?>
