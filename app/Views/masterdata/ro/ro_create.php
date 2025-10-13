<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h4 class="mb-4 text-cintaraja"><i class="bi bi-plus-square me-2"></i>Tambah Data Rincian Output</h4>

<form action="<?= base_url('ro/store'); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="ro_id" class="form-label">Rincian Output</label>
        <select name="kro_id" id="kro_id" class="form-select" required>
            <option value="">-- Pilih KRO --</option>
            <?php foreach ($kros as $k): ?>
                <option value="<?= $k['id']; ?>"><?= $k['kode_kro']; ?> - <?= $k['nama_kro']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_ro" class="form-label">Kode RO</label>
        <input type="text" name="kode_ro" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nama_ro" class="form-label">Nama RO</label>
        <input type="text" name="nama_ro" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="<?= base_url('kro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>
