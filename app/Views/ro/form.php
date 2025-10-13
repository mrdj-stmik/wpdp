<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-list-task me-2"></i>Form Input RO</h3>

<form action="<?= base_url('ro/simpan'); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="program_id" class="form-label">Program</label>
        <select name="program_id" id="program_id" class="form-select" required>
            <option value="">-- Pilih Program --</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?= $p['id']; ?>"><?= $p['kode_program']; ?> - <?= $p['nama_program']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

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
        <label for="kro_id" class="form-label">KRO</label>
        <select name="kro_id" id="kro_id" class="form-select" required>
            <option value="">-- Pilih KRO --</option>
            <?php foreach ($kros as $kro): ?>
                <option value="<?= $kro['id']; ?>"><?= $kro['kode_kro']; ?> - <?= $kro['nama_kro']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_ro" class="form-label">Kode RO</label>
        <input type="text" name="kode_ro" id="kode_ro" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nama_ro" class="form-label">Nama RO</label>
        <input type="text" name="nama_ro" id="nama_ro" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="<?= base_url('ro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>