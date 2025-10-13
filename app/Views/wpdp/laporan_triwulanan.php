<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja">
    <i class="bi bi-bar-chart me-2"></i>Rekap WPDP Triwulanan
</h3>

<!-- Form Filter Triwulan -->
<form method="get" class="mb-3">
    <div class="row align-items-center">
        <div class="col-md-3">
            <label for="triwulan" class="form-label">Pilih Triwulan:</label>
            <select name="triwulan" id="triwulan" class="form-select" onchange="this.form.submit()">
                <option value="1" <?= $triwulan == '1' ? 'selected' : ''; ?>>Triwulan 1 (Jan - Mar)</option>
                <option value="2" <?= $triwulan == '2' ? 'selected' : ''; ?>>Triwulan 2 (Apr - Jun)</option>
                <option value="3" <?= $triwulan == '3' ? 'selected' : ''; ?>>Triwulan 3 (Jul - Sep)</option>
                <option value="4" <?= $triwulan == '4' ? 'selected' : ''; ?>>Triwulan 4 (Okt - Des)</option>
            </select>
        </div>
    </div>
</form>

<a href="<?= base_url('wpdp'); ?>" class="btn btn-secondary mb-3">
    <i class="bi bi-arrow-left-circle"></i> Kembali ke WPDP Bulanan
</a>

<a href="<?= base_url('wpdp/export-triwulan?triwulan=' . $triwulan); ?>" class="btn btn-outline-danger mb-3">
    <i class="bi bi-file-earmark-excel"></i> Export Output Triwulanan
</a>

<table class="table table-bordered table-striped table-hover small">
    <thead class="table-secondary">
        <tr>
            <th>No</th>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>KRO</th>
            <th>RO</th>
            <th>Bulan</th>
            <th>Anggaran</th>
            <th>Realisasi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rekap)): ?>
            <?php $no = 1; foreach ($rekap as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= esc($row['nama_program']); ?></td>
                    <td><?= esc($row['nama_kegiatan']); ?></td>
                    <td><?= esc($row['nama_kro']); ?></td>
                    <td><?= esc($row['nama_ro']); ?></td>
                    <td><?= esc($row['bulan']); ?></td>
                    <td><?= number_format($row['usulan_wp'], 0, ',', '.'); ?></td>
                    <td><?= number_format($row['realisasi_dp'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk triwulan ini.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
