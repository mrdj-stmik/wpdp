<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Edit Detil Kegiatan</h3>

<form action="<?= base_url('detil/update/' . $detil['id']); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="mb-3">
        <label for="kode_detil" class="form-label">Kode Detil</label>
        <input type="text" class="form-control" id="kode_detil" name="kode_detil" value="<?= esc($detil['kode_detil']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama_detil" class="form-label">Nama Detil</label>
        <input type="text" class="form-control" id="nama_detil" name="nama_detil" value="<?= esc($detil['nama_detil']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="pagu_anggaran_awal" class="form-label">Pagu Anggaran Awal</label>
        <input type="number" class="form-control" id="pagu_anggaran_awal" name="pagu_anggaran_awal" value="<?= esc($detil['pagu_anggaran_awal']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="akun_id" class="form-label">Akun</label>
        <select class="form-control" id="akun_id" name="akun_id" required>
            <option value="">Pilih Akun</option>
            <?php if (isset($akuns) && is_array($akuns)): ?>
                <?php foreach ($akuns as $akun): ?>
                    <option value="<?= esc($akun['id']); ?>" <?= ($akun['id'] == $detil['akun_id']) ? 'selected' : ''; ?>>
                        <?= esc($akun['kode_akun']); ?> - <?= esc($akun['nama_akun']); ?>
                        <?php if (!empty($akun['nama_ro'])): ?>
                            (RO: <?= esc($akun['nama_ro']); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="<?= base_url('detil'); ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection(); ?>