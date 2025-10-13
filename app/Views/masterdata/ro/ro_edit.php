<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h4 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Edit Data Rincian Output</h4>

<form action="<?= base_url('ro/update/' . $ro['id']); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="kro_id" class="form-label">KRO</label>
        <select name="kro_id" id="kro_id" class="form-select" required>
            <?php foreach ($kros as $k): ?>
                <option value="<?= $k['id']; ?>" <?= $k['id'] == $ro['kro_id'] ? 'selected' : ''; ?>>
                    <?= $k['kode_kro']; ?> - <?= $k['nama_kro']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_ro" class="form-label">Kode Rincian Output</label>
        <input type="text" name="kode_ro" class="form-control" value="<?= $ro['kode_ro']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="nama_ro" class="form-label">Nama Rincian Output</label>
        <input type="text" name="nama_ro" class="form-control" value="<?= $ro['nama_ro']; ?>" required>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
    <a href="<?= base_url('kro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>
