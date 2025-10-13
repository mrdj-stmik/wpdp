<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4">Tambah Kegiatan</h3>

<form action="<?= base_url('kegiatan/store'); ?>" method="post">
  <div class="mb-3">
    <label>Program</label>
    <select name="program_id" class="form-control" required>
      <?php foreach ($programs as $p): ?>
        <option value="<?= $p['id']; ?>"><?= $p['nama_program']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Kode Kegiatan</label>
    <input type="text" name="kode_kegiatan" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Nama Kegiatan</label>
    <input type="text" name="nama_kegiatan" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?= $this->endSection(); ?>
