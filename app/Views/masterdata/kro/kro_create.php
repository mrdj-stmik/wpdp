<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h4 class="mb-4 text-cintaraja"><i class="bi bi-plus-square me-2"></i>Tambah Data KRO</h4>

<form action="<?= base_url('kro/store'); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="kegiatan_id" class="form-label">Kegiatan</label>
        <select name="kegiatan_id" id="kegiatan_id" class="form-select" required>
            <option value="">-- Pilih Kegiatan --</option>
            <?php foreach ($kegiatans as $k): ?>
                <option value="<?= $k['id']; ?>"><?= $k['kode_kegiatan']; ?> - <?= $k['nama_kegiatan']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_kro" class="form-label">Kode KRO</label>
        <input type="text" name="kode_kro" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nama_kro" class="form-label">Nama KRO</label>
        <input type="text" name="nama_kro" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="<?= base_url('kro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>
