<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-boxes me-2"></i>Form Input KRO</h3>

<form action="<?= base_url('kro/simpan'); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="mb-3">
        <label for="program_id" class="form-label">Program</label>
        <select name="program_id" id="program_id" class="form-select" required>
            <option value="">-- Pilih Program --</option>
            <?php foreach ($programs as $program): ?>
                <option value="<?= $program['id']; ?>"><?= $program['kode_program']; ?> - <?= $program['nama_program']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kegiatan_id" class="form-label">Kegiatan</label>
        <select name="kegiatan_id" id="kegiatan_id" class="form-select" required>
            <option value="">-- Pilih Kegiatan --</option>
            <?php foreach ($kegiatans as $kegiatan): ?>
                <option value="<?= $kegiatan['id']; ?>"><?= $kegiatan['kode_kegiatan']; ?> - <?= $kegiatan['nama_kegiatan']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="kode_kro" class="form-label">Kode KRO</label>
        <input type="text" name="kode_kro" id="kode_kro" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nama_kro" class="form-label">Nama KRO</label>
        <input type="text" name="nama_kro" id="nama_kro" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="<?= base_url('kro'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>