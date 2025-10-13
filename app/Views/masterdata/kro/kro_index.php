<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-folder2-open me-2"></i>Data Kelompok Rincian Output</h3>

<a href="<?= base_url('kro/create'); ?>" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Tambah KRO</a>

<table class="table table-bordered table-hover">
    <thead class="table-success">
        <tr>
            <th>Kegiatan</th>
            <th>Kode KRO</th>
            <th>Nama KRO</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kros as $kro): ?>
            <tr>
                <td><?= $kro['nama_kegiatan']; ?></td>
                <td><?= $kro['kode_kro']; ?></td>
                <td><?= $kro['nama_kro']; ?></td>
                <td>
                    <a href="<?= base_url('kro/edit/'.$kro['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('kro/delete/'.$kro['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
