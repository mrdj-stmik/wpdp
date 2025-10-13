<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja">Edit Program</h3>

<form action="<?= base_url('program/update/' . $program['id']); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="mb-3">
        <label>Kode Program</label>
        <input type="text" name="kode_program" class="form-control" value="<?= $program['kode_program']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Nama Program</label>
        <input type="text" name="nama_program" class="form-control" value="<?= $program['nama_program']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('program'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>
