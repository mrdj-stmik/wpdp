<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <h3 class="mb-4 text-cintaraja"><i class="bi bi-bar-chart-line me-2"></i>Laporan Capaian per RO Tahun
        <?= esc($tahun); ?>
    </h3>

    <a href="<?= base_url('wpdp'); ?>" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left-circle"></i> Kembali ke WPDP Bulanan
    </a>

    <a href="<?= base_url('laporan/export-komprehensif/2025') ?>" class="btn btn-outline-danger mb-3">
        <i class="bi bi-file-earmark-excel"></i> Export Output Triwulanan
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover small">
            <thead class="table-success">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Sasaran</th>
                    <th rowspan="2">Indikator</th>
                    <th rowspan="2">Target</th>
                    <th colspan="2">Rincian Output</th>
                    <th rowspan="2">Nama PMO</th>
                    <th rowspan="2">Nama Ketua Tim</th>
                    <th rowspan="2">Pagu Awal Detil (RO)</th>
                    <th rowspan="2">Capaian Output (RO)</th>
                    <th rowspan="2">Persentase Capaian (%)</th>
                    <th rowspan="2">Rekap Kendala</th>
                    <th rowspan="2">Rekap Solusi</th>
                    <th rowspan="2">Rekap Rencana Tindak Lanjut</th>
                    <th rowspan="2">Rekap Batas Tindak Lanjut</th>
                </tr>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reportData)): ?>
                    <tr>
                        <td colspan="17" class="text-center">Tidak ada data laporan untuk tahun <?= esc($tahun); ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($reportData as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= esc($row['sasaran']); ?></td>
                            <td><?= esc($row['indikator']); ?></td>
                            <td>100%</td>
                            <td><?= esc($row['kode_ro']); ?></td>
                            <td><?= esc($row['nama_ro']); ?></td>
                            <td><?= esc($row['nama_pmo'] ?? '-'); ?></td>
                            <td><?= esc($row['nama_ketua_tim'] ?? '-'); ?></td>
                            <td><?= esc($row['total_pagu_awal_detil_ro_formatted']); ?></td>
                            <td><?= esc($row['total_realisasi_ro_formatted']); ?></td>
                            <!-- Ini mungkin perlu perhatian, lihat point 2 -->
                            <td><?= esc($row['persentase_capaian']); ?>%</td>
                            <td><?= nl2br(esc($row['rekap_kendala'])); ?></td>
                            <td><?= nl2br(esc($row['rekap_solusi'])); ?></td>
                            <td><?= nl2br(esc($row['rekap_rencana_tindak_lanjut'])); ?></td>
                            <td><?= nl2br(esc($row['rekap_batas_tindak_lanjut'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection(); ?>