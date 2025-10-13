<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h4 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Edit Data KRO</h4>

<form action="<?= base_url('kro/update/' . $kro['id']); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="kegiatan_id" class="form-label">Kegiatan</label>
        <select name="kegiatan_id" id="kegiatan_id" class="form-select" required>
            <?php foreach ($kegiatans as $k): ?>
                <option value="<?= $k['id']; ?>" <?= $k['id'] == $kro['kegiatan_id'] ? 'selected' : ''; ?>>
                    <?= $k['kode_kegiatan']; ?> - <?= $k['nama_kegiatan']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_kro" class="form-label">Kode KRO</label>
        <input type="text" name="kode_kro" class="form-control" value="<?= $kro['kode_kro']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="nama_kro" class="form-label">Nama KRO</label>
        <input type="text" name="nama_kro" class="form-control" value="<?= $kro['nama_kro']; ?>" required>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
    <a href="<?= base_url('kro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>
