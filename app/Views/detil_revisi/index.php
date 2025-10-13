<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-list-ul me-2"></i>Daftar Revisi Detil</h3>
<p><strong>ID Detil:</strong> <?= esc($id_detil); ?></p>

<!-- Pesan Flash -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tabel Daftar Revisi -->
<div class="table-responsive">
    <table class="table table-bordered table-hover small" id="revisiTable">
        <thead class="table-success text-center">
            <tr>
                <th>No</th>
                <th>Revisi Ke</th>
                <th>Volume</th>
                <th>Harga Satuan</th>
                <th>Jumlah Biaya</th>
                <th>Catatan</th>
                <th>SD/CP</th>
                <th>Tanggal Revisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($revisions)): ?>
                <?php foreach ($revisions as $index => $revision): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= esc($revision['revisi_ke']); ?></td>
                        <td><?= number_format($revision['volume'], 2, ',', '.'); ?></td>
                        <td>Rp <?= number_format($revision['harga_satuan'], 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($revision['jumlah_biaya'], 0, ',', '.'); ?></td>
                        <td><?= esc($revision['catatan']); ?></td>
                        <td><?= esc($revision['sd_cp']); ?></td>
                        <td><?= date('d-m-Y', strtotime($revision['tanggal_revisi'])); ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('detilrevisi/edit/' . $revision['id']); ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            <form action="<?= base_url('detilrevisi/delete/' . $revision['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus revisi ini?')">
                                <?= csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data revisi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-3">
    <?= $pager->links(); ?>
</div>

<!-- Tombol Tambah Revisi -->
<a href="<?= base_url('detilrevisi/create/' . $id_detil); ?>" class="btn btn-primary mt-3"><i class="bi bi-plus-circle"></i> Tambah Revisi</a>

<?= $this->endSection(); ?>