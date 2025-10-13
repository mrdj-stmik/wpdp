<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Form Input WPDP Bulanan</h3>

<form action="<?= base_url('wpdp/simpan') ?>" method="post" data-baseurl="<?= base_url() ?>"
    data-is-editing-existing="<?= isset($is_editing_existing) && $is_editing_existing ? 'true' : 'false' ?>"
    data-is-creating-from-ro="<?= isset($is_creating_from_ro) && $is_creating_from_ro ? 'true' : 'false' ?>"
    data-selected-program="<?= $selected['program_id'] ?? '' ?>"
    data-selected-kegiatan="<?= $selected['kegiatan_id'] ?? '' ?>" data-selected-kro="<?= $selected['kro_id'] ?? '' ?>"
    data-selected-ro="<?= $selected['ro_id'] ?? '' ?>" data-selected-komponen="<?= $selected['komponen_id'] ?? '' ?>"
    data-selected-akun="<?= $selected['akun_id'] ?? '' ?>" data-selected-detil="<?= $selected['detil_id'] ?? '' ?>">
    <?= csrf_field() ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="program_id" class="form-label">Program</label>
            <select class="form-select" name="program_id" id="program_id" <?= (isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro) ? 'disabled' : '' ?>
            required>
            <option value="">-- Pilih Program --</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($selected['program_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                    <?= $p['kode_program'] ?> - <?= $p['nama_program'] ?>
                </option>
            <?php endforeach; ?>
            </select>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): ?>
            <input type="hidden" name="program_id" value="<?= $selected['program_id'] ?>">
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="kegiatan_id" class="form-label">Kegiatan</label>
            <select class="form-select" name="kegiatan_id" id="kegiatan_id" <?= (isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro) ? 'disabled' : '' ?>
            required>
            <option value="">-- Pilih Kegiatan --</option>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): // Tampilkan opsi terpilih jika disabled ?>
                <?php if (isset($kegiatans)):
                    foreach ($kegiatans as $k): ?>
                        <option value="<?= $k['id'] ?>" selected><?= $k['kode_kegiatan'] ?> - <?= $k['nama_kegiatan'] ?></option>
                    <?php endforeach; endif; ?>
            <?php endif; ?>
            </select>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): ?>
            <input type="hidden" name="kegiatan_id" value="<?= $selected['kegiatan_id'] ?>">
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col mb-6">
            <label for="kro_id" class="form-label">KRO</label>
            <select class="form-select" name="kro_id" id="kro_id" <?= (isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro) ? 'disabled' : '' ?> required>
            <option value="">-- Pilih KRO --</option>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): // Tampilkan opsi terpilih jika disabled ?>
                <?php if (isset($kros)):
                    foreach ($kros as $k): ?>
                        <option value="<?= $k['id'] ?>" selected><?= $k['kode_kro'] ?> - <?= $k['nama_kro'] ?></option>
                    <?php endforeach; endif; ?>
            <?php endif; ?>
            </select>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): ?>
            <input type="hidden" name="kro_id" value="<?= $selected['kro_id'] ?>">
            <?php endif; ?>
        </div>
        <div class="col mb-6">
            <label for="ro_id" class="form-label">RO</label>
            <select class="form-select" name="ro_id" id="ro_id" <?= (isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro) ? 'disabled' : '' ?> required>
            <option value="">-- Pilih RO --</option>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): // Tampilkan opsi terpilih jika disabled ?>
                <?php if (isset($ros)):
                    foreach ($ros as $r): ?>
                        <option value="<?= $r['id'] ?>" selected><?= $r['kode_ro'] ?> - <?= $r['nama_ro'] ?></option>
                    <?php endforeach; endif; ?>
            <?php endif; ?>
            </select>
            <?php if ((isset($is_editing_existing) && $is_editing_existing) || (isset($is_creating_from_ro) && $is_creating_from_ro)): ?>
            <input type="hidden" name="ro_id" value="<?= $selected['ro_id'] ?>">
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-3">
    <div class="col mb-3">
        <label for="komponen_id" class="form-label">Komponen</label>
        <select class="form-select" name="komponen_id" id="komponen_id" required>
            <option value="">-- Pilih Komponen --</option>
            <?php if (isset($is_editing_existing) && $is_editing_existing && isset($komponens)):
                foreach ($komponens as $ko): ?>
                    <option value="<?= $ko['id'] ?>" selected><?= $ko['kode_komponen'] ?> - <?= $ko['nama_komponen'] ?></option>
                <?php endforeach; endif; ?>
        </select>
    </div>
    <div class="col mb-3">
        <label for="akun_id" class="form-label">Akun</label>
        <select class="form-select" name="akun_id" id="akun_id" required>
            <option value="">-- Pilih Akun --</option>
            <?php // Opsi akan diisi oleh JS. Di mode edit, PHP hanya perlu menyediakan opsi yang terpilih ?>
            <?php if (isset($is_editing_existing) && $is_editing_existing && isset($akuns)):
                foreach ($akuns as $a): ?>
                    <option value="<?= $a['id'] ?>" selected><?= $a['kode_akun'] ?> - <?= $a['nama_akun'] ?></option>
                <?php endforeach; endif; ?>
        </select>
    </div>
    </div>
    <div class="row mb-3 justify-content-center">
    <style>
    #detil_id {
        max-width: 800px; /* Batasi lebar dropdown */
        margin: 0 auto; /* Posisikan di tengah */
    }
</style>
        <div class="col md-4">
            <label for="detil_id" class="form-label text-center d-block">Detil</label>
            <select class="form-select" name="detil_id" id="detil_id" required>
                <option value="">-- Pilih Detil --</option>
                <?php // Opsi akan diisi oleh JS. Di mode edit, PHP hanya perlu menyediakan opsi yang terpilih ?>
                <?php if (isset($is_editing_existing) && $is_editing_existing && isset($detils)):
                foreach ($detils as $d): ?>
                    <option value="<?= $d['id'] ?>" selected><?= $d['kode_detil'] ?> - <?= $d['nama_detil'] ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 mb-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select" required>
                <option value="">-- Pilih Bulan --</option>
                <?php
                $bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $selected_bulan = $wpdp['bulan'] ?? ($selected['bulan'] ?? ''); // Ambil dari wpdp atau selected
                ?>
                <?php foreach ($bulan_list as $b): ?>
                    <option value="<?= $b; ?>" <?= $selected_bulan == $b ? 'selected' : '' ?>><?= $b; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-3 mb-3">
                 <label for="anggaran_tersedia_display" class="form-label">Pagu Tersedia (Rp)</label>
                 <input type="text" id="anggaran_tersedia_display" class="form-control" value="" disabled>
             </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Usulan WP (Rp)</label>
            <input type="text" name="usulan_wp" id="usulan_wp" class="form-control" inputmode="numeric"
                value="<?= number_format($wpdp['usulan_wp'] ?? ($selected['usulan_wp'] ?? 0), 0, ',', '.') ?>" required>
            <small style ="color:red" class="form-text text-muted">Sekaligus input Rencana Pengajuan Dana</small>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Realisasi DP(Rp)</label>
            <input type="text" name="realisasi_dp" id="realisasi_dp" class="form-control" inputmode="numeric"
                value="<?= number_format($wpdp['realisasi_dp'] ?? ($selected['realisasi_dp'] ?? 0), 0, ',', '.') ?>"
                required>
            <small class="form-text text-muted">Biarkan kosong jika belum realisasi pada bulan pengajuan</small>
        </div>
    </div>

    <div class="form-group">
        <label for="kendala">Kendala</label>
        <small class="form-text text-muted">("-" atau diisi jika ada kendala pada bulan sebelumnya)</small>
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
        <small class="form-text text-muted">("-" atau diisi jika ada solusi untuk kendala pada bulan sebelumnya)</small>
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
    </div>


    <div class="mb-3 mt-3">
        <label class="form-label">Memo (Isi Catatan atau "-")</label>
        <textarea name="memo" class="form-control" rows="4"><?= esc($wpdp['memo'] ?? ($selected['memo'] ?? '')) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="<?= base_url('wpdp'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php // Link ke file JavaScript terpisah ?>
<script src="<?= base_url('assets/js/wpdp_form.js') ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->endSection(); ?>