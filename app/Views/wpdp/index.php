<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-table me-2"></i>Data WPDP Bulanan</h3>

<a href="<?= base_url('wpdp/form'); ?>" class="btn btn-primary mb-3"><i class="bi bi-plus-circle me-1"></i>Tambah Data</a>
<a href="<?= base_url('wpdp/export'); ?>" class="btn btn-success mb-3">
    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
</a>

<a href="<?= base_url('wpdp/export-triwulan'); ?>" class="btn btn-outline-danger mb-3">
    <i class="bi bi-file-earmark-excel"></i> Export Output Triwulanan
</a>

<a href="<?= base_url('wpdp/laporan-triwulan'); ?>" class="btn btn-outline-primary mb-3">
    <i class="bi bi-bar-chart"></i> Lihat Rekap Triwulan
</a>

<a href="<?= base_url('wpdp/laporan_komprehensif'); ?>" class="btn btn-info mb-3">
    <i class="bi bi-bar-chart"></i> Lihat Rekap Untuk FRA
</a>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover small" id="wpdpTable"> <thead class="table-success">
            <tr>
                <th>No</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>KRO</th>
                <th>RO</th>
                <th>Komponen</th>
                <th>Akun</th> <th>Detil</th> <th>Bulan</th>
                <th>Pagu Revisi</th> <th>Pagu Tersisa</th> <th>Usulan WP</th>
                <th>Realisasi DP</th>
                <th>Memo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($wpdp as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($row['nama_program']); ?></td>
                <td><?= esc($row['nama_kegiatan']); ?></td>
                <td><?= esc($row['nama_kro']); ?></td>
                <td><?= esc($row['nama_ro']); ?></td>
                <td><?= esc($row['nama_komponen']); ?></td>
                <td><?= esc($row['nama_akun']); ?></td> <td><?= esc($row['nama_detil']); ?></td> <td><?= esc($row['bulan']); ?></td>
                <td><?= number_format(esc($row['pagu_anggaran_awal']), 0, ',', '.'); ?></td> <td><?= number_format(esc($row['pagu_tersisa']), 0, ',', '.'); ?></td> <td><?= number_format(esc($row['usulan_wp']), 0, ',', '.'); ?></td>
                <td><?= number_format(esc($row['realisasi_dp']), 0, ',', '.'); ?></td>
                <td><?= esc(substr($row['memo'], 0, 100)); ?>...</td> <td>
                    
                    <a href="<?= base_url('wpdp/edit/' . $row['id']); ?>" class="btn btn-success btn-sm mb-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="<?= base_url('wpdp/delete/' . $row['id']) ?>" method="post" style="display:inline"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> 
<script>
$(document).ready(function() {
    $('#wpdpTable').DataTable();
});
</script>
<?= $this->endSection(); ?>
