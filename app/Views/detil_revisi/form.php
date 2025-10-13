<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i><?= isset($revisi_data) ? 'Edit Revisi Detil' : 'Tambah Revisi Detil Baru'; ?></h3>

    <?php $session = \Config\Services::session(); ?>
    <?php if ($session->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $session->getFlashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($session->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($session->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= isset($revisi_data) ? site_url('detilrevisi/update/' . $revisi_data['id']) : site_url('detilrevisi/store'); ?>" method="post">
        <?= csrf_field(); ?>

        <input type="hidden" name="id_detil" value="<?= htmlspecialchars($id_detil); ?>">

        <div class="mb-3">
            <label for="revisi_ke" class="form-label">Revisi Ke:</label>
            <input type="number" class="form-control" id="revisi_ke" name="revisi_ke" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['revisi_ke']) : ''; ?>" <?= isset($revisi_data) ? 'readonly' : 'required'; ?>>
            <?php if (isset($revisi_data)): ?>
                <small class="text-muted">Nomor revisi tidak bisa diubah.</small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="volume" class="form-label">Volume:</label>
            <input type="number" class="form-control" id="volume" name="volume" step="0.01" min="0" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['volume']) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan:</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" step="0.01" min="0" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['harga_satuan']) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label for="jumlah_biaya" class="form-label">Jumlah Biaya:</label>
            <input type="number" class="form-control" id="jumlah_biaya" name="jumlah_biaya" step="0.01" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['jumlah_biaya']) : ''; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan (1 karakter):</label>
            <input type="text" class="form-control" id="catatan" name="catatan" maxlength="50" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['catatan']) : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="sd_cp" class="form-label">SD/CP (2 karakter):</label>
            <input type="text" class="form-control" id="sd_cp" name="sd_cp" maxlength="2" value="<?= isset($revisi_data) ? htmlspecialchars($revisi_data['sd_cp']) : ''; ?>">
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <a href="<?= site_url('detilrevisi/index/' . $id_detil); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const volumeInput = document.getElementById('volume');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const jumlahBiayaInput = document.getElementById('jumlah_biaya');

    function calculateTotal() {
        const volume = parseFloat(volumeInput.value) || 0;
        const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
        const jumlahBiaya = volume * hargaSatuan;
        jumlahBiayaInput.value = jumlahBiaya.toFixed(2);
    }

    volumeInput.addEventListener('input', calculateTotal);
    hargaSatuanInput.addEventListener('input', calculateTotal);

    // Calculate on load if values exist
    calculateTotal();
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection(); ?>