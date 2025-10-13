<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4">Edit Kegiatan</h3>

<form action="<?= base_url('kegiatan/update/' . $kegiatan['id']); ?>" method="post">
  <div class="mb-3">
    <label>Program</label>
    <select name="program_id" class="form-control" required>
      <?php foreach ($programs as $p): ?>
        <option value="<?= $p['id']; ?>" <?= $p['id'] == $kegiatan['program_id'] ? 'selected' : ''; ?>>
          <?= $p['nama_program']; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Kode Kegiatan</label>
    <input type="text" name="kode_kegiatan" class="form-control" value="<?= $kegiatan['kode_kegiatan']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Nama Kegiatan</label>
    <input type="text" name="nama_kegiatan" class="form-control" value="<?= $kegiatan['nama_kegiatan']; ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>

<?= $this->endSection(); ?>
