<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Edit Kendala & Solusi</h3>

<form action="<?= base_url('wpdp/update-kendala-solusi/' . $wpdp['id']); ?>" method="post">
    <div class="mb-3">
        <label for="kendala" class="form-label">Kendala</label>
        <textarea name="kendala" class="form-control" rows="4"><?= esc($wpdp['kendala']) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="solusi" class="form-label">Solusi</label>
        <textarea name="solusi" class="form-control" rows="4"><?= esc($wpdp['solusi']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
    <a href="<?= base_url('wpdp'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</form>

<?= $this->endSection(); ?>
