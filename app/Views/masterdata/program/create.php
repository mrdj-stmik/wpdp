<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4">Tambah Program</h3>

<form action="<?= base_url('program/store'); ?>" method="post">
  <div class="mb-3">
    <label>Kode Program</label>
    <input type="text" name="kode_program" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Nama Program</label>
    <input type="text" name="nama_program" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?= $this->endSection(); ?>