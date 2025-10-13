<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-cintaraja"><i class="bi bi-database-fill-gear me-2"></i>Master Data Rincian Output (RO)</h3>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="programSelect" class="form-label">1. Program</label>
        <select id="programSelect" class="form-select">
            <option value="">-- Pilih Program --</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?= $p['id']; ?>"><?= $p['kode_program']; ?>     <?= $p['nama_program']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="kegiatanSelect" class="form-label">2. Kegiatan</label>
        <select id="kegiatanSelect" class="form-select">
            <option value="">-- Pilih Kegiatan --</option>
        </select>
    </div>
</div>

<h5 class="text-success mb-3">List <strong>Rincian Output (RO)</strong> dari <span class="text-warning"
        id="selectedKegiatanLabel">Kegiatan Terpilih</span></h5>

<table class="table table-bordered table-striped">
    <thead class="table-success">
        <tr>
            <th width="50">No.</th>
            <th>Kode RO</th>
            <th>Nama RO</th>
            <th width="220">Aksi</th>
        </tr>
    </thead>
    <tbody id="roList">
        <tr>
            <td colspan="4" class="text-center text-muted">Silakan pilih kegiatan</td>
        </tr>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const programSelect = document.getElementById('programSelect');
        const kegiatanSelect = document.getElementById('kegiatanSelect');
        const roList = document.getElementById('roList');
        const selectedKegiatanLabel = document.getElementById('selectedKegiatanLabel');

        // Fungsi helper untuk mengisi tabel RO
        function populateRoTable(data) {
            if (data.length > 0) {
                roList.innerHTML = ''; // Kosongkan tabel
                data.forEach((ro, index) => {
                    roList.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${ro.kode_ro}</td>
                            <td>${ro.nama_ro}</td>
                            <td>
                            <a href="<?= base_url('wpdp/createFromRo') ?>/${ro.id}" class="btn btn-sm btn-success me-1">Input WPDP</a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                roList.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Belum ada RO untuk pilihan ini.</td></tr>`;
            }
        }

        programSelect.addEventListener('change', () => {
            const programId = programSelect.value;

            kegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan --</option>'; // Reset Kegiatan
            // Tabel RO direset ke pesan default saat Program berubah
            roList.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Silakan pilih kegiatan</td></tr>`;
            selectedKegiatanLabel.textContent = 'Kegiatan Terpilih'; // Reset Label

            if (programId) {
                // --- PANGGILAN AJAX 1: Muat Kegiatan berdasarkan Program (untuk dropdown Kegiatan) ---
                fetch(`<?= base_url('masterdata/kegiatan') ?>/${programId}`)
                    .then(res => {
                        if (!res.ok) throw new Error(res.statusText); // Basic error check
                        return res.json();
                    })
                    .then(data => {
                        data.forEach(k => {
                            kegiatanSelect.innerHTML += `<option value="${k.id}" data-nama="${k.nama_kegiatan}">${k.kode_kegiatan} - ${k.nama_kegiatan}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading kegiatan:', error)); // Tambahkan error handling

                // --- PANGGILAN AJAX 2 (Dihapus): Tidak perlu memuat RO berdasarkan Program di sini ---
                // fetch(`<?= base_url('masterdata/ro-list-by-program') ?>/${programId}`)... // BARIS INI DIHAPUS
            }
        });

        kegiatanSelect.addEventListener('change', () => {
            const kegiatanId = kegiatanSelect.value;
            const selectedOption = kegiatanSelect.selectedOptions[0];
            const kegiatanNama = selectedOption && selectedOption.dataset.nama ? selectedOption.dataset.nama : 'Kegiatan Terpilih';
            selectedKegiatanLabel.textContent = kegiatanNama;

            // Reset Tabel RO setiap kali Kegiatan berubah
            roList.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Silakan pilih kegiatan</td></tr>`;


            // Jika Kegiatan dipilih (bukan opsi default "-- Pilih Kegiatan --")
            if (kegiatanId) {
                // --- PANGGILAN AJAX 3: Muat RO berdasarkan Kegiatan (untuk mengisi tabel RO) ---
                fetch(`<?= base_url('masterdata/ro-list') ?>/${kegiatanId}`) // URL endpoint yang sudah ada
                    .then(res => {
                        if (!res.ok) throw new Error(res.statusText); // Basic error check
                        return res.json();
                    })
                    .then(data => {
                        populateRoTable(data); // Gunakan fungsi helper untuk mengisi tabel
                    })
                    .catch(error => console.error('Error loading RO by kegiatan:', error)); // Tambahkan error handling

            }
            // Jika Kegiatan kembali ke "-- Pilih Kegiatan --", tabel RO tetap menunjukkan pesan default dari baris reset di atas.
        });

        // --- Opsional: Logika untuk memuat Kegiatan awal jika ada Program yang sudah terpilih saat load ---
        // Berguna jika form ini bisa dibuka dengan Program yang sudah pre-selected oleh PHP
        // if (programSelect.value) {
        //      const initialProgramId = programSelect.value;
        //      fetch(`<?= base_url('masterdata/kegiatan') ?>/${initialProgramId}`)
        //         .then(res => res.json())
        //         .then(data => { /* isi kegiatanSelect */ }) // Isi dropdown Kegiatan
        //         .catch(error => console.error('Error loading initial kegiatan:', error));
        //      // Tidak perlu memuat RO di sini, karena RO hanya muncul setelah Kegiatan dipilih
        // }

    });
</script>

<?= $this->endSection(); ?>