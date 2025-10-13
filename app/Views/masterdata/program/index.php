<!-- app/Views/masterdata/program/index.php -->
<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-folder2-open me-2"></i>Data Program</h3>
<a href="<?= base_url('program/create'); ?>" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Tambah Program</a>

<table class="table table-bordered">
  <thead class="table-success">
    <tr>
      <th>Kode</th>
      <th>Nama</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($programs as $p): ?>
      <tr>
        <td><?= $p['kode_program']; ?></td>
        <td><?= $p['nama_program']; ?></td>
        <td>
          <a href="<?= base_url('program/edit/' . $p['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
          <form action="<?= base_url('program/delete/' . $p['id']); ?>" method="post" style="display:inline;">
            <?= csrf_field(); ?>
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->endSection(); ?>
