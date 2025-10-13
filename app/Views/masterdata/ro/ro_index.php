<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-folder2-open me-2"></i>Data Rincian Output</h3>

<a href="<?= base_url('ro/create'); ?>" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Tambah RO</a>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Kelompok Rincian Output</th>
            <th>Kode RO</th>
            <th>Nama RO</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ros as $ro): ?>
            <tr>
                <td><?= $ro['nama_kro']; ?></td>
                <td><?= $ro['kode_ro']; ?></td>
                <td><?= $ro['nama_ro']; ?></td>
                <td>
                    <a href="<?= base_url('ro/edit/'.$ro['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('ro/delete/'.$ro['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
