<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-plus-circle me-2"></i>Tambah Detil Kegiatan</h3>

<?php $session = \Config\Services::session(); ?>
<?php if ($session->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($session->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('detil/store'); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="mb-3">
        <label for="kode_detil_input" class="form-label">Kode Detil (6 digit)</label>
        <input type="text" class="form-control" id="kode_detil_input" name="kode_detil_input" maxlength="6" pattern="[0-9]{6}" placeholder="Contoh: 521811" value="<?= old('kode_detil_input') ?>" required>
        <small class="text-muted">Masukkan 6 digit kode detil, kode lengkap akan dibuat otomatis</small>
    </div>
    <div class="mb-3">
        <label for="nama_detil" class="form-label">Nama Detil</label>
        <input type="text" class="form-control" id="nama_detil" name="nama_detil" value="<?= old('nama_detil') ?>" required>
    </div>
    <div class="mb-3">
        <label for="pagu_anggaran_awal" class="form-label">Pagu Anggaran Awal</label>
        <input type="number" class="form-control" id="pagu_anggaran_awal" name="pagu_anggaran_awal" value="<?= old('pagu_anggaran_awal') ?>" min="0" required>
    </div>
    <div class="mb-3">
        <label for="nilai_pagu_terakhir" class="form-label">Nilai Pagu Terakhir</label>
        <input type="number" class="form-control" id="nilai_pagu_terakhir" name="nilai_pagu_terakhir" value="<?= old('nilai_pagu_terakhir') ?>" min="0">
        <small class="text-muted">Opsional - jika kosong akan sama dengan pagu anggaran awal</small>
    </div>
    <div class="mb-3">
        <label for="akun_id" class="form-label">Akun</label>
        <select class="form-control" id="akun_id" name="akun_id" required>
            <option value="">Pilih Akun</option>
            <?php if (isset($akuns) && is_array($akuns)): ?>
                <?php foreach ($akuns as $akun): ?>
                    <option value="<?= esc($akun['id']); ?>" <?= (old('akun_id') == $akun['id']) ? 'selected' : ''; ?>>
                        <?= esc($akun['kode_akun']); ?> - <?= esc($akun['nama_akun']); ?>
                        <?php if (!empty($akun['nama_ro'])): ?>
                            (RO: <?= esc($akun['nama_ro']); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Preview Kode Detil Lengkap</label>
        <div class="alert alert-info" id="kode_preview">
            <small>Pilih akun dan masukkan 6 digit kode detil untuk melihat preview</small>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('detil'); ?>" class="btn btn-secondary">Batal</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const akunSelect = document.getElementById('akun_id');
    const kodeDetilInput = document.getElementById('kode_detil_input');
    const kodePreview = document.getElementById('kode_preview');
    
    // Data akun dari PHP
    const akunData = <?= json_encode($akuns ?? []) ?>;
    
    function updatePreview() {
        const selectedAkunId = akunSelect.value;
        const kodeInput = kodeDetilInput.value;
        
        if (selectedAkunId && kodeInput && kodeInput.length === 6) {
            const selectedAkun = akunData.find(a => a.id == selectedAkunId);
            if (selectedAkun) {
                const kodeAkun = selectedAkun.kode_akun;
                const prefix = kodeAkun.substring(0, kodeAkun.length - 6);
                const kodeDetilLengkap = prefix + kodeInput;
                kodePreview.innerHTML = `<strong>Kode Detil Lengkap:</strong> ${kodeDetilLengkap}`;
            }
        } else {
            kodePreview.innerHTML = '<small>Pilih akun dan masukkan 6 digit kode detil untuk melihat preview</small>';
        }
    }
    
    akunSelect.addEventListener('change', updatePreview);
    kodeDetilInput.addEventListener('input', updatePreview);
});
</script>

<?= $this->endSection(); ?>