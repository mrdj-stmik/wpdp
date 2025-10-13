<?= $this->extend('layouts/main'); ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-folder2-open me-2"></i>Data Detil Kegiatan</h3>

<a href="<?= base_url('detil/create'); ?>" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Tambah Detil Kegiatan</a>

<table class="table table-bordered table-hover small" id="detilTable" style="width:100%" small>
    <thead class="table-success" style="text-align: center;">
        <tr>
            <th>Nama RO</th>
            <th>Nama Akun</th>
            <th>Kode Detil</th>
            <th>Nama Detil</th>
            <th>Pagu Awal</th>
            <th>Pagu Revisi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detils as $detil): ?>
            <tr>
                <td><?= esc($detil['nama_ro']); ?></td>
                <td><?= esc($detil['nama_akun']); ?></td> <!-- Menggunakan nama_akun dari join -->
                <td><?= esc($detil['kode_detil']); ?></td>
                <td><?= esc($detil['nama_detil']); ?></td>
                <td style="text-align: right;"><?= number_format(esc($detil['pagu_anggaran_awal']), 0, ',', '.'); ?></td>
                <td style="text-align: right;"><?= number_format(esc($detil['pagu_revisi_terakhir']), 0, ',', '.'); ?></td>
                <td>
                    <a href="<?= base_url('detil/edit/'.$detil['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form action="<?= base_url('detil/delete/'.$detil['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                    <a href="<?= base_url('detilrevisi/index/'.$detil['id']); ?>" class="btn btn-sm btn-primary">Revisi Detil</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> 
<script>
$(document).ready(function() {
    $('#detilTable').DataTable();
});
</script>
<?= $this->endSection(); ?>

<?= $this->endSection(); ?>