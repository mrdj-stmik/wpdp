<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Edit WPDP Bulanan</h3>

<form action="<?= base_url('wpdp/update/' . $wpdp['id']); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="row">
        <!-- Program -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Program</label>
            <input type="hidden" name="program_id" value="<?= $selectedProgram['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedProgram['kode_program']; ?> - <?= $selectedProgram['nama_program']; ?>" readonly>
        </div>

        <!-- Kegiatan -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Kegiatan</label>
            <input type="hidden" name="kegiatan_id" value="<?= $selectedKegiatan['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedKegiatan['kode_kegiatan']; ?> - <?= $selectedKegiatan['nama_kegiatan']; ?>" readonly>
        </div>

        <!-- KRO -->
        <div class="col-md-6 mb-3">
            <label class="form-label">KRO</label>
            <input type="hidden" name="kro_id" value="<?= $selectedKRO['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedKRO['kode_kro']; ?> - <?= $selectedKRO['nama_kro']; ?>" readonly>
        </div>

        <!-- RO -->
        <div class="col-md-6 mb-3">
            <label class="form-label">RO</label>
            <input type="hidden" name="ro_id" value="<?= $selectedRO['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedRO['kode_ro']; ?> - <?= $selectedRO['nama_ro']; ?>" readonly>
        </div>

        <!-- Komponen -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Komponen</label>
            <input type="hidden" name="komponen_id" value="<?= $selectedKomponen['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedKomponen['kode_komponen']; ?> - <?= $selectedKomponen['nama_komponen']; ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Akun</label>
            <input type="hidden" name="akun_id" value="<?= $selectedAkun['id']; ?>">
            <input type="text" class="form-control" value="<?= $selectedAkun['kode_akun']; ?> - <?= $selectedAkun['nama_akun']; ?>" readonly>
        </div>
        <div class="row mb-3 justify-content-center">
            <style>
                #detil_id {
                    max-width: 800px;
                    margin: 0 auto;
                }
            </style>
            <div class="col md-4">
                <label for="detil_id" class="form-label text-center d-block">Detil</label>
                <input type="hidden" name="detil_id" value="<?= $selectedDetil['id']; ?>">
                <input type="text" class="form-control" value="<?= $selectedDetil['kode_detil']; ?> - <?= $selectedDetil['nama_detil']; ?>" readonly>
            </div>
        </div>
    </div>

    <!-- Bulan, Anggaran, Realisasi -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-select" required>
                <option value="">-- Pilih Bulan --</option>
                <?php foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b): ?>
                    <option value="<?= $b; ?>" <?= $wpdp['bulan'] == $b ? 'selected' : ''; ?>><?= $b; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Usulan WP (Rp)</label>
            <input type="text" name="usulan_wp" id="usulan_wp" class="form-control" inputmode="numeric"
                value="<?= number_format($wpdp['usulan_wp'] ?? ($selected['usulan_wp'] ?? 0), 0, ',', '.') ?>" required>
            <small style ="color:red" class="form-text text-muted">Sekaligus input Rencana Pengajuan Dana</small>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Realisasi DP(Rp)</label>
            <input type="text" name="realisasi_dp" id="realisasi_dp" class="form-control" inputmode="numeric"
                value="<?= number_format($wpdp['realisasi_dp'] ?? ($selected['realisasi_dp'] ?? 0), 0, ',', '.') ?>"
                required>
            <small class="form-text text-muted">Biarkan kosong jika belum realisasi pada bulan pengajuan</small>
        </div>
    </div>

    <div class="form-group">
        <label for="kendala">Kendala</label>
        <small class="form-text text-muted">(diisi jika ada kendala pada bulan sebelumnya)</small>
        <div id="kendala-container">
            <?php
            // Ambil list kendala dari controller, default ke array kosong jika null
            $kendala_list = $kendala_list ?? [];
            // Filter kendala yang kosong atau hanya spasi
            $filtered_kendala = array_filter($kendala_list, fn($k) => trim($k) !== '');

            // Jika setelah filter list-nya kosong, siapkan satu elemen kosong untuk ditampilkan
            $display_list_kendala = !empty($filtered_kendala) ? $filtered_kendala : [''];
            ?>
            <?php foreach ($display_list_kendala as $k): ?>
                <div class="input-group mb-2">
                    <input type="text" name="kendala[]" class="form-control" placeholder="Masukkan kendala"
                        value="<?= esc($k) ?>">
                    <button type="button" class="btn btn-danger remove-kendala">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-kendala" class="btn btn-warning mt-2">+ Tambah Kendala</button>
    </div>

    <div class="form-group mt-3">
        <label for="solusi">Solusi</label>
        <small class="form-text text-muted">(diisi jika ada solusi untuk kendala pada bulan sebelumnya)</small>
        <div id="solusi-container">
            <?php
            // Ambil list solusi dari controller, default ke array kosong jika null
            $solusi_list = $solusi_list ?? [];
            // Filter solusi yang kosong atau hanya spasi
            $filtered_solusi = array_filter($solusi_list, fn($s) => trim($s) !== '');

            // Jika setelah filter list-nya kosong, siapkan satu elemen kosong untuk ditampilkan
            $display_list_solusi = !empty($filtered_solusi) ? $filtered_solusi : [''];
            ?>
            <?php foreach ($display_list_solusi as $s): ?>
                <div class="input-group mb-2">
                    <input type="text" name="solusi[]" class="form-control" placeholder="Masukkan solusi"
                        value="<?= esc($s) ?>">
                    <button type="button" class="btn btn-danger remove-solusi">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-solusi" class="btn btn-warning mt-2">+ Tambah Solusi</button>
   

    <div class="mb-3">
        <label class="form-label">Memo (Opsional)</label>
        <textarea name="memo" class="form-control" rows="4"><?= $wpdp['memo']; ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
    <a href="<?= base_url('wpdp'); ?>" class="btn btn-secondary">Batal</a>
</form>

<?php // Link ke file JavaScript terpisah ?>
<script src="<?= base_url('assets/js/wpdp_form.js') ?>"></script>

<?= $this->endSection(); ?>
