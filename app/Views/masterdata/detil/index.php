<!-- filepath: c:\xampp\htdocs\WPDP\app\Views\wpdp\rekap_ro_komponen.php -->
<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-table me-2"></i>Rekapitulasi Pagu Revisi Terakhir per RO dan Komponen</h3>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama RO</th>
                <th>Nama Komponen</th>
                <th>Total Pagu Revisi Terakhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekapitulasi)): ?>
                <?php $no = 1; ?>
                <?php foreach ($rekapitulasi as $row): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= esc($row['nama_ro']); ?></td>
                        <td><?= esc($row['nama_komponen']); ?></td>
                        <td class="text-end"><?= number_format($row['total_pagu_revisi_terakhir'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data rekapitulasi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="<?= base_url('detil/index'); ?>" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection(); ?>