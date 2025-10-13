<!-- filepath: c:\xampp\htdocs\WPDP\app\Views\rpd\index.php -->
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <h3 class="mb-4 text-cintaraja"><i class="bi bi-cash me-2"></i>Laporan Ketersediaan Dana Detail TA <?= esc($tahun); ?></h3>

    <div class="mb-3">
        <p class="mb-0"><strong>Keterangan Instansi:</strong></p>
        <p class="mb-0 ms-3">Kementerian : 054 BADAN PUSAT STATISTIK</p>
        <p class="mb-0 ms-3">Unit Organisasi : 01 BADAN PUSAT STATISTIK</p>
        <p class="mb-0 ms-3">Satuan Kerja : 018712 BADAN PUSAT STATISTIK KAB. TASIKMALAYA</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <a href="<?= base_url('wpdp'); ?>" class="btn btn-primary mr-2">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke WPDP Bulanan
            </a>

            <a href="<?= base_url('rpd/export/?tahun=' . esc($tahun)) ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Laporan RPD
            </a>
        </div>
        <form action="" method="get" class="d-flex align-items-center gap-2">
    <label for="search_term" class="form-label mb-0">CARI:</label>
    <?php $searchTerm = $_GET['search'] ?? ''; ?>
    <input type="text" class="form-control form-control-sm" id="search_term" name="search" value="<?= esc($searchTerm); ?>" placeholder="Kode/Nama Akun, Detil, Komponen, dll.">
    <button type="submit" class="btn btn-primary btn-sm">Search</button>
    <a href="<?= base_url('rpd'); ?>" class="btn btn-secondary btn-sm">Reset</a>
</form>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Data Rencana Penarikan Dana
        </div>
        <div class="card-body">
        <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                <table id="rpdTable" class="table table-bordered table-striped table-hover table-sm small" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="text-center align-middle" rowspan="2">Uraian</th>
                            <th class="align-middle" rowspan="2">Pagu Revisi</th>
                            <th rowspan="2">Lock Pagu</th>
                            <th class="align-middle" rowspan="2">Sisa Pagu</th>
                            <th colspan="12" class="text-center">Rencana Penarikan Dana (Rp)</th>
                            <th class="align-middle" rowspan="2">JUMLAH</th>
                            <th class="align-middle" rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>JAN</th>
                            <th>FEB</th>
                            <th>MAR</th>
                            <th>APR</th>
                            <th>MEI</th>
                            <th>JUN</th>
                            <th>JUL</th>
                            <th>AGT</th>
                            <th>SEP</th>
                            <th>OKT</th>
                            <th>NOV</th>
                            <th>DES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reportData)): ?>
                            <tr>
                                <td colspan="19" class="text-center">Tidak ada data RPD untuk tahun <?= esc($tahun); ?>.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($reportData as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?= esc($row['kode_komponen'] . ' ' . $row['nama_komponen'] ?? '-') ?><br>
                                        <?= esc($row['kode_detil'] . ' ' . $row['nama_detil'] ?? '-') ?>
                                    </td>
                                    <td class="text-end"><?= number_format($row['pagu_revisi'] ?? 0, 0, ',', '.'); ?></td>
                                    <td class="text-end"><?= $row['lock_pagu'] ?? ''; ?></td>
                                    <td class="text-end"><?= number_format($row['sisa_pagu'] ?? 0, 0, ',', '.'); ?></td>
                                    <td class="text-end <?= isset($row['is_realisasi_januari']) && $row['is_realisasi_januari'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['januari']) && $row['januari'] > 0 ? number_format($row['januari'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_februari']) && $row['is_realisasi_februari'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['februari']) && $row['februari'] > 0 ? number_format($row['februari'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_maret']) && $row['is_realisasi_maret'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['maret']) && $row['maret'] > 0 ? number_format($row['maret'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_april']) && $row['is_realisasi_april'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['april']) && $row['april'] > 0 ? number_format($row['april'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_mei']) && $row['is_realisasi_mei'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['mei']) && $row['mei'] > 0 ? number_format($row['mei'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_juni']) && $row['is_realisasi_juni'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['juni']) && $row['juni'] > 0 ? number_format($row['juni'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_juli']) && $row['is_realisasi_juli'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['juli']) && $row['juli'] > 0 ? number_format($row['juli'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_agustus']) && $row['is_realisasi_agustus'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['agustus']) && $row['agustus'] > 0 ? number_format($row['agustus'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_september']) && $row['is_realisasi_september'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['september']) && $row['september'] > 0 ? number_format($row['september'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_oktober']) && $row['is_realisasi_oktober'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['oktober']) && $row['oktober'] > 0 ? number_format($row['oktober'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_november']) && $row['is_realisasi_november'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['november']) && $row['november'] > 0 ? number_format($row['november'], 0, ',', '.') : ''; ?>
</td>
<td class="text-end <?= isset($row['is_realisasi_desember']) && $row['is_realisasi_desember'] ? 'rpd-realized' : ''; ?>">
    <?= isset($row['desember']) && $row['desember'] > 0 ? number_format($row['desember'], 0, ',', '.') : ''; ?>
</td>
                                    <td class="text-end"><?= number_format($row['jumlah_rpd'] ?? 0, 0, ',', '.'); ?></td>
                                    <td>
                                        <?= nl2br(esc($row['keterangan_rpd_rekap'] ?? '-')) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style untuk sel RPD yang sudah direalisasikan */
    .rpd-realized {
        background-color: #d4edda !important; /* Warna hijau muda */
        font-weight: bold; /* Opsional: tebalkan teks */
        color: #155724; /* Opsional: warna teks lebih gelap */
        /* Anda bisa menyesuaikan style ini */
    }
    /* Menambahkan sedikit padding pada form group untuk tampilan yang lebih baik */
    .form-inline .form-group {
        margin-bottom: 0.5rem; /* Sesuaikan jika perlu */
    }
    /* Mengatur lebar kolom agar tidak terlalu sempit */
    .table th, .table td {
        white-space: wrap; /* Mencegah teks wrap di sel tabel */
    }
    /* Style untuk membuat header tabel sticky */
    /* Diterapkan pada thead di dalam table-responsive */
    .table-responsive thead th {
        position: sticky;
        top: 0; /* Tetap di bagian atas container */
        z-index: 5; /* Pastikan header di atas konten lain saat scrolling */
        background-color: #dee2e6;; /* Warna latar belakang agar konten di belakangnya tidak terlihat */
        /* Sesuaikan warna latar belakang agar sesuai tema Anda */
    }
    /* Pastikan baris kedua header juga sticky */
     .table-responsive thead tr:nth-child(2) th {
        top: 33px; /* Sesuaikan offset jika tinggi baris pertama berubah */
        /* Offset ini harus sama dengan tinggi baris pertama thead */
     }

     /* Style tambahan untuk memastikan border terlihat saat sticky */
     .table-responsive thead th {
         border-bottom: 1px solid #dee2e6; /* Tambahkan border bawah jika hilang */
     }


</style>


<?= $this->endSection(); ?>