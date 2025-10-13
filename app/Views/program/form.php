<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-diagram-2-fill me-2"></i>Form Input Kegiatan</h3>

<form action="<?= base_url('kegiatan/simpan'); ?>" method="post">
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
        <label for="kode_kegiatan" class="form-label">Kode Kegiatan</label>
        <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="<?= base_url('kegiatan'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>