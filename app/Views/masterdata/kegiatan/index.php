<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-folder2-open me-2"></i>Data Kegiatan</h3>
<a href="<?= base_url('kegiatan/create'); ?>" class="btn btn-success mb-3">Tambah Kegiatan</a>

<table class="table table-bordered">
  <thead class="table-success">
    <tr>
      <th>Program</th>
      <th>Kode</th>
      <th>Nama Kegiatan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($kegiatan as $k): ?>
      <tr>
        <td><?= $k['nama_program']; ?></td>
        <td><?= $k['kode_kegiatan']; ?></td>
        <td><?= $k['nama_kegiatan']; ?></td>
        <td>
          <a href="<?= base_url('kegiatan/edit/' . $k['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
          <form action="<?= base_url('kegiatan/delete/' . $k['id']); ?>" method="post" style="display:inline;">
            <?= csrf_field(); ?>
            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->endSection(); ?>
