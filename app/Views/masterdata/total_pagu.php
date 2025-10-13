
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4 text-success"><i class="bi bi-database-fill-gear me-2"></i>TOTAL PAGU PER RO</h2>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th class="align-middle">No</th> <!-- Tambahkan kolom untuk nomor urut -->
                <th class="text-center align-middle">Program</th>
                <th class="text-center align-middle">Kegiatan</th>
                <th class="text-center align-middle">RO</th>
                <th class="text-center align-middle">Total Pagu Awal</th>
                <th class="text-center align-middle">Total Pagu Revisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($totalPagu)): ?>
                <?php $no = 1; // Inisialisasi nomor urut ?>
                <?php foreach ($totalPagu as $row): ?>
                    <tr>
                        <td><?= $no++; ?></td> <!-- Tampilkan nomor urut -->
                        <td><?= esc($row['nama_program']); ?></td>
                        <td><?= esc($row['nama_kegiatan']); ?></td>
                        <td><?= esc($row['nama_ro']); ?></td>
                        <td class="text-end align-middle text-nowrap">Rp <?= number_format($row['total_pagu_awal'], 0, ',', '.'); ?></td>
                        <td class="text-end align-middle text-nowrap">Rp <?= number_format($row['total_pagu_revisi'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td> <!-- Sesuaikan colspan -->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>