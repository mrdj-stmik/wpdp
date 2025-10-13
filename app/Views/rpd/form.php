<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <h3 class="mb-4 text-cintaraja"><i class="bi bi-pencil-square me-2"></i>Form Input Rencana Penarikan Dana</h3>

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
     <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Validasi Gagal!</h4>
            <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error); ?></li>
            <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Masukkan Data RPD Bulanan
        </div>
        <div class="card-body">
            <form action="<?= base_url('rpd/simpan'); ?>" method="post" id="rpdForm">
                <?= csrf_field(); ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="tahun" name="tahun" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php foreach ($available_years as $year): ?>
                                <option value="<?= esc($year['tahun']); ?>" <?= old('tahun', $year['tahun']); ?>>
                                    <?= esc($year['tahun']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                        <select class="form-select" id="bulan" name="bulan" required>
                            <option value="">-- Pilih Bulan --</option>
                            <?php foreach ($months as $month): ?>
                                <option value="<?= esc($month); ?>" <?= old('bulan', $month); ?>>
                                    <?= esc($month); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="col-md-6">
                         <label for="pagu_revisi_tersedia_display" class="form-label">Pagu Revisi Tersedia (Rp)</label>
                         <input type="text" class="form-control" id="pagu_revisi_tersedia_display" value="0" disabled>
                         <small class="form-text text-muted">Pagu revisi terakhir dikurangi total realisasi DP setahun.</small>
                     </div>
                </div>

                <div class="row g-3 mb-3">
                     <div class="col-md-6">
                        <label for="program_id" class="form-label">Program <span class="text-danger">*</span></label>
                        <select class="form-select" id="program_id" name="program_id" required>
                            <option value="">-- Pilih Program --</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= esc($program['id']); ?>" <?= old('program_id', $program['id']); ?>>
                                    <?= esc($program['nama_program']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kegiatan_id" class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select" id="kegiatan_id" name="kegiatan_id" required disabled>
                            <option value="">-- Pilih Kegiatan --</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                     <div class="col-md-6">
                        <label for="kro_id" class="form-label">KRO <span class="text-danger">*</span></label>
                        <select class="form-select" id="kro_id" name="kro_id" required disabled>
                            <option value="">-- Pilih KRO --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="ro_id" class="form-label">RO <span class="text-danger">*</span></label>
                        <select class="form-select" id="ro_id" name="ro_id" required disabled>
                            <option value="">-- Pilih RO --</option>
                        </select>
                    </div>
                </div>

                 <div class="row g-3 mb-3">
                     <div class="col-md-6">
                        <label for="komponen_id" class="form-label">Komponen <span class="text-danger">*</span></label>
                        <select class="form-select" id="komponen_id" name="komponen_id" required disabled>
                            <option value="">-- Pilih Komponen --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="akun_id" class="form-label">Akun <span class="text-danger">*</span></label>
                        <select class="form-select" id="akun_id" name="akun_id" required disabled>
                            <option value="">-- Pilih Akun --</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                     <div class="col-md-12">
                        <label for="detil_id" class="form-label">Detil <span class="text-danger">*</span></label>
                        <select class="form-select" id="detil_id" name="detil_id" required disabled>
                            <option value="">-- Pilih Detil --</option>
                        </select>
                    </div>
                </div>

                <hr> <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="rencana_penarikan_dana" class="form-label">Rencana Penarikan Dana (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="rencana_penarikan_dana" name="rencana_penarikan_dana" step="0.01" value="<?= old('rencana_penarikan_dana', 0); ?>" required min="0">
                         <div class="invalid-feedback" id="rencana_penarikan_dana_error">
                             </div>
                    </div>
                     <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= old('keterangan'); ?></textarea>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan Data RPD
                    </button>
                     <a href="<?= base_url('rpd'); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Laporan RPD
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log("RPD form script loaded.");

    const $tahun = $('#tahun');
    const $bulan = $('#bulan');
    const $programId = $('#program_id');
    const $kegiatanId = $('#kegiatan_id');
    const $kroId = $('#kro_id');
    const $roId = $('#ro_id');
    const $komponenId = $('#komponen_id');
    const $akunId = $('#akun_id');
    const $detilId = $('#detil_id');
    const $rencanaPenarikanDana = $('#rencana_penarikan_dana');
    const $keterangan = $('#keterangan');
    const $paguRevisiTersediaDisplay = $('#pagu_revisi_tersedia_display');
    const $rencanaPenarikanDanaError = $('#rencana_penarikan_dana_error');

    let availablePagu = 0; // Variabel untuk menyimpan nilai pagu tersedia dari AJAX
    let currentRpdValue = 0; // Variabel untuk menyimpan nilai RPD bulan ini jika sedang edit

    // Fungsi untuk reset dan disable dropdown di bawah level yang dipilih
    function resetAndDisable(startLevel) {
        console.log("Resetting dropdowns starting from:", startLevel);
        const levels = ['program', 'kegiatan', 'kro', 'ro', 'komponen', 'akun', 'detil'];
        let startIndex = levels.indexOf(startLevel);
        if (startIndex === -1) startIndex = 0;

        for (let i = startIndex + 1; i < levels.length; i++) {
            const targetId = '#' + levels[i] + '_id';
            console.log("Resetting and disabling:", targetId);
            $(targetId).html('<option value="">-- Pilih ' + levels[i].charAt(0).toUpperCase() + levels[i].slice(1) + ' --</option>').prop('disabled', true);
        }
         // Reset RPD fields and pagu display when hierarchy changes
         $rencanaPenarikanDana.val(0);
         $keterangan.val('');
         $paguRevisiTersediaDisplay.val(0); // Reset pagu display
         availablePagu = 0; // Reset availablePagu variable
         currentRpdValue = 0; // Reset currentRpdValue
         console.log("RPD fields, pagu display, and currentRpdValue reset.");
    }

    // Fungsi untuk mengambil data dropdown via AJAX
    function loadDropdown(parentId, targetDropdownId, urlSegment, placeholderText) {
        const parentIdVal = $('#' + parentId).val();
        const targetDropdown = $('#' + targetDropdownId);

        console.log("Loading dropdown:", targetDropdownId, "for parent:", parentId, "with value:", parentIdVal);

        resetAndDisable(parentId.replace('_id', ''));

        if (parentIdVal) {
            targetDropdown.prop('disabled', true).html('<option value="">-- Memuat... --</option>');
            $.ajax({
                url: '<?= base_url('ajaxcontroller/'); ?>' + urlSegment + '/' + parentIdVal,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Data received for", targetDropdownId, ":", data);
                    targetDropdown.html('<option value="">-- Pilih ' + placeholderText + ' --</option>');
                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                             const itemId = item.id;
                             let itemName = '';
                             let itemKode = '';
                             if (urlSegment === 'kegiatan') {
                                 itemName = item.nama_kegiatan; itemKode = item.kode_kegiatan;
                             } else if (urlSegment === 'kro') {
                                 itemName = item.nama_kro; itemKode = item.kode_kro;
                             } else if (urlSegment === 'ro') {
                                 itemName = item.nama_ro; itemKode = item.kode_ro;
                             } else if (urlSegment === 'komponen') {
                                 itemName = item.nama_komponen; itemKode = item.kode_komponen;
                             } else if (urlSegment === 'akun') {
                                 itemName = item.nama_akun; itemKode = item.kode_akun;
                             } else if (urlSegment === 'detil') {
                                 itemName = item.nama_detil; itemKode = item.kode_detil;
                             }

                             const display = itemKode ? itemKode + ' - ' + itemName : itemName;

                            targetDropdown.append('<option value="' + itemId + '">' + display + '</option>');
                        });
                        targetDropdown.prop('disabled', false);
                    } else {
                        console.log("No data found for", targetDropdownId);
                         targetDropdown.html('<option value="">-- Tidak ada data --</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error loading " + targetDropdownId + ": " + status, error, xhr);
                    targetDropdown.html('<option value="">-- Gagal memuat data --</option>');
                    alert('Gagal memuat data ' + placeholderText + '. Silakan coba lagi.');
                }
            });
        }
    }

    // Fungsi untuk mengambil data RPD Existing dan Pagu Tersedia
    function fetchRpdDataAndAvailablePagu() {
        const detilId = $detilId.val();
        const tahun = $tahun.val();
        const bulan = $bulan.val();

        console.log("Fetching RPD data and available pagu:", { detilId, tahun, bulan });

        // Reset RPD fields and pagu display initially
        $rencanaPenarikanDana.val(0);
        $keterangan.val('');
        $paguRevisiTersediaDisplay.val(0);
        availablePagu = 0;
        currentRpdValue = 0; // Reset currentRpdValue
        $rencanaPenarikanDana.removeClass('is-invalid');
        $rencanaPenarikanDanaError.text('');


        // Hanya jalankan AJAX jika Detil dan Tahun sudah terpilih
        if (detilId && tahun) {
             // --- AJAX call untuk mengambil Pagu Revisi Tersedia (Pagu Revisi Terakhir - Total Realisasi DP Setahun) ---
             $.ajax({
                 url: '<?= base_url('rpd/getAvailablePaguRpd'); ?>', // Rute untuk pagu tersedia
                 method: 'POST',
                 data: {
                     detil_id: detilId,
                     tahun: tahun,
                     <?= csrf_token() ?>: '<?= csrf_hash() ?>' // Kirim token CSRF
                 },
                 dataType: 'json',
                 success: function(paguResponse) {
                     console.log("Available Pagu response:", paguResponse);
                     if (paguResponse.status === 'success') {
                         availablePagu = paguResponse.available_pagu; // Simpan nilai pagu
                         // Format angka sebelum ditampilkan
                         $paguRevisiTersediaDisplay.val(numberFormat(availablePagu));

                         // --- Setelah pagu tersedia didapat, baru cek RPD existing jika bulan juga dipilih ---
                         if (bulan) {
                              $.ajax({
                                  url: '<?= base_url('rpd/getExistingRpd'); ?>', // Rute untuk ambil data existing
                                  method: 'POST',
                                  data: {
                                      detil_id: detilId,
                                      tahun: tahun,
                                      bulan: bulan,
                                      <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                                  },
                                  dataType: 'json',
                                  success: function(response) {
                                      console.log("Existing RPD response:", response);
                                      if (response.status === 'success') {
                                          // Data ditemukan, isi form dengan data existing
                                          $rencanaPenarikanDana.val(response.data.rencana_penarikan_dana);
                                          $keterangan.val(response.data.keterangan);
                                          currentRpdValue = parseFloat(response.data.rencana_penarikan_dana) || 0; // Simpan nilai RPD lama
                                      } else {
                                          // Data belum ada atau error, reset form RPD
                                          $rencanaPenarikanDana.val(0);
                                          $keterangan.val('');
                                          currentRpdValue = 0; // Reset nilai RPD lama
                                      }
                                       // Lakukan validasi client-side setelah semua data dimuat
                                       validateRencanaPenarikanDana();
                                  },
                                  error: function(xhr, status, error) {
                                      console.error("AJAX Error fetching existing RPD: " + status, error, xhr);
                                      alert('Gagal memuat data RPD existing. Silakan coba lagi.');
                                       $rencanaPenarikanDana.val(0);
                                       $keterangan.val('');
                                       currentRpdValue = 0; // Reset nilai RPD lama
                                       // Validasi akan berjalan dengan nilai default
                                       validateRencanaPenarikanDana();
                                  }
                              });
                         } else {
                              // Jika bulan belum dipilih, reset RPD fields dan validasi
                              $rencanaPenarikanDana.val(0);
                              $keterangan.val('');
                              currentRpdValue = 0;
                              validateRencanaPenarikanDana(); // Validasi akan berjalan dengan nilai 0
                         }

                     } else {
                         console.error('Error fetching available pagu:', paguResponse.message);
                         $paguRevisiTersediaDisplay.val('Error');
                         availablePagu = 0; // Reset availablePagu
                         currentRpdValue = 0; // Reset currentRpdValue
                         // Reset RPD fields dan validasi
                         $rencanaPenarikanDana.val(0);
                         $keterangan.val('');
                         validateRencanaPenarikanDana();
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error("AJAX Error fetching available pagu: " + status, error, xhr);
                     $paguRevisiTersediaDisplay.val('Error');
                     availablePagu = 0; // Reset availablePagu
                     currentRpdValue = 0; // Reset currentRpdValue
                     // Reset RPD fields dan validasi
                     $rencanaPenarikanDana.val(0);
                     $keterangan.val('');
                     validateRencanaPenarikanDana();
                 }
             });

        } else {
             // Jika Detil atau Tahun belum dipilih, reset semua
             $rencanaPenarikanDana.val(0);
             $keterangan.val('');
             $paguRevisiTersediaDisplay.val(0);
             availablePagu = 0;
             currentRpdValue = 0;
             console.log("Detil or Tahun not selected. RPD fields and pagu display reset.");
             validateRencanaPenarikanDana();
        }
    }

    // Fungsi untuk format angka (mirip number_format PHP)
    function numberFormat(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        const n = !isFinite(+number) ? 0 : +number;
        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
        const dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
        let s = '';

        const toFixedFix = function (n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
            } else {
                const arr = ('' + n).split('e');
                let sig = '';
                if (+arr[1] + prec > 0) {
                    sig = '+';
                }
                return (n = Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec))) / (Math.pow(10, sig + (+arr[1] + prec)));
            }
        };

        s = (toFixedFix(n, prec)).toFixed(prec);

        const i = sep ? s.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep) : s.split('.')[0];
        s = (prec) ? i + dec + (s.split('.')[1] || '') : i;

        return s;
    }


    // --- Validasi Client-Side ---
    // Validasi ini memastikan input RPD bulan ini + total RPD bulan lain setahun <= Pagu Revisi Terakhir
    function validateRencanaPenarikanDana() {
        const detilId = $detilId.val();
        const tahun = $tahun.val();
        const bulan = $bulan.val();
        const inputVal = parseFloat($rencanaPenarikanDana.val()) || 0;

         // Hanya validasi jika Detil, Tahun, dan Bulan sudah dipilih
         // dan availablePagu sudah dimuat
        if (detilId && tahun && bulan && availablePagu !== null) { // Check if availablePagu is loaded
             // Validasi client-side yang akurat:
             // Cek apakah (nilai input saat ini) <= (Pagu Revisi Terakhir - Total RPD Setahun Excluding Bulan Ini)
             // Kita perlu Pagu Revisi Terakhir dan Total RPD Setahun Excluding Bulan Ini dari server.
             // Endpoint getAvailablePaguRpd saat ini hanya mengembalikan Pagu Revisi - Total Realisasi DP.

             // Untuk validasi client-side yang akurat, kita perlu endpoint AJAX lain
             // atau endpoint getAvailablePaguRpd diubah untuk mengembalikan data yang dibutuhkan.

             // ALTERNATIF Sederhana untuk Client-Side (Kurang Akurat tapi Cepat):
             // Cek apakah input bulan ini <= availablePagu (Pagu Revisi - Total Realisasi DP)
             // Ini HANYA indikasi awal, validasi server-side yang akurat akan berjalan saat simpan.
             // const available = availablePagu; // Pagu Revisi Terakhir - Total Realisasi DP Setahun

             // if (inputVal > available) {
             //     $rencanaPenarikanDana.addClass('is-invalid');
             //     $rencanaPenarikanDanaError.text('Rencana Penarikan Dana (Rp ' + numberFormat(inputVal) + ') melebihi pagu yang tersedia (Rp ' + numberFormat(available) + '). Catatan: Validasi akurat dilakukan di server.');
             // } else {
             //     $rencanaPenarikanDana.removeClass('is-invalid');
             //     $rencanaPenarikanDanaError.text('');
             // }

             // --- Validasi Client-Side yang Lebih Akurat (Memerlukan Data Tambahan) ---
             // Jika endpoint getAvailablePaguRpd diubah untuk mengembalikan Pagu Revisi Terakhir dan Total RPD Setahun Excluding Bulan Ini,
             // maka validasi client-side bisa dilakukan di sini dengan formula:
             // inputVal + totalRpdSetahunExcludingThisMonth <= paguRevisiTerakhir

             // Karena endpoint belum diubah, kita kembali ke validasi server-side sebagai yang utama.
             // Validasi client-side di sini hanya untuk memberikan feedback visual awal.

             // Validasi client-side: Cek apakah input bulan ini + total RPD bulan lain setahun <= Pagu Revisi Terakhir
             // Endpoint getAvailablePaguRpd memberikan (Pagu Revisi Terakhir - Total Realisasi DP Setahun)
             // Kita perlu Total RPD Setahun Excluding Bulan Ini dari server.
             // Atau, kita bisa hitung di client-side: Total RPD Setahun Excluding Bulan Ini = (Total RPD Setahun) - (RPD Bulan Ini)
             // Total RPD Setahun bisa didapat dari endpoint lain atau dihitung di server.

             // Untuk menyederhanakan, mari kita gunakan validasi server-side sebagai yang utama.
             // Validasi client-side hanya memberikan peringatan jika input bulan ini melebihi Pagu Revisi Tersedia (Pagu Revisi - Total Realisasi DP).
             // Ini bukan validasi yang mencegah total setahun melebihi pagu, tapi indikasi awal.

             const available = availablePagu; // Ini adalah Pagu Revisi Terakhir - Total Realisasi DP Setahun

             // Validasi Client-side Sederhana (Indikasi Awal):
             // Cek apakah input bulan ini > Pagu Revisi Tersedia (Pagu Revisi - Total Realisasi DP)
             // Ini tidak mencegah total setahun melebihi pagu, hanya indikasi.
             // if (inputVal > available) {
             //     $rencanaPenarikanDana.addClass('is-invalid');
             //     $rencanaPenarikanDanaError.text('Rencana Penarikan Dana (Rp ' + numberFormat(inputVal) + ') melebihi pagu yang tersedia (Rp ' + numberFormat(available) + '). Catatan: Validasi akurat dilakukan di server.');
             // } else {
             //     $rencanaPenarikanDana.removeClass('is-invalid');
             //     $rencanaPenarikanDanaError.text('');
             // }

             // Validasi Client-side yang Lebih Baik (Memerlukan currentRpdValue):
             // Cek apakah (inputVal - currentRpdValue) > availablePagu
             // availablePagu = Pagu Revisi Terakhir - Total Realisasi DP Setahun
             // Jika inputVal > availablePagu + currentRpdValue, maka total RPD setahun akan melebihi Pagu Revisi Terakhir
             // availablePagu + currentRpdValue adalah "Pagu Revisi Terakhir - Total Realisasi DP Setahun + RPD Lama Bulan Ini"
             // Ini adalah pagu yang tersisa jika kita mengganti nilai RPD lama dengan 0.
             // Validasi yang benar adalah: inputVal <= Pagu Revisi Terakhir - (Total RPD Setahun Excluding Bulan Ini)
             // Total RPD Setahun Excluding Bulan Ini = (Total RPD Setahun) - (RPD Bulan Ini)
             // Total RPD Setahun bisa didapat dari endpoint lain atau dihitung di server.

             // Mari kita gunakan validasi client-side yang cek terhadap Pagu Revisi Tersedia (Pagu Revisi - Total Realisasi DP)
             // dan berikan pesan yang jelas bahwa validasi akurat ada di server.

             const availableForThisMonth = availablePagu + currentRpdValue; // Pagu Revisi Terakhir - Total Realisasi DP + RPD Lama Bulan Ini

             if (inputVal > availableForThisMonth) {
                 $rencanaPenarikanDana.addClass('is-invalid');
                 $rencanaPenarikanDanaError.text('Rencana Penarikan Dana (Rp ' + numberFormat(inputVal) + ') melebihi pagu yang tersedia untuk bulan ini (Rp ' + numberFormat(availableForThisMonth) + '). Validasi total RPD setahun dilakukan di server.');
             } else {
                 $rencanaPenarikanDana.removeClass('is-invalid');
                 $rencanaPenarikanDanaError.text('');
             }


        } else {
             // Jika ada dropdown yang belum dipilih, hapus pesan error
             $rencanaPenarikanDana.removeClass('is-invalid');
             $rencanaPenarikanDanaError.text('');
        }
    }

    // Event listeners untuk cascading dropdown
    $('#program_id').change(function() {
        loadDropdown('program_id', 'kegiatan_id', 'kegiatan', 'Kegiatan');
    });

    $('#kegiatan_id').change(function() {
        loadDropdown('kegiatan_id', 'kro_id', 'kro', 'KRO');
    });

    $('#kro_id').change(function() {
        loadDropdown('kro_id', 'ro_id', 'ro', 'RO');
    });

    $('#ro_id').change(function() {
        loadDropdown('ro_id', 'komponen_id', 'komponen', 'Komponen');
    });

    $('#komponen_id').change(function() {
        loadDropdown('komponen_id', 'akun_id', 'akun', 'Akun');
    });

    $('#akun_id').change(function() {
        loadDropdown('akun_id', 'detil_id', 'detil', 'Detil');
    });

    // --- Panggil fungsi fetchRpdDataAndAvailablePagu saat Detil, Tahun, atau Bulan Berubah ---
    $('#detil_id, #tahun, #bulan').change(function() {
        fetchRpdDataAndAvailablePagu();
    });

    // --- Validasi client-side saat input rencana penarikan dana berubah ---
    $rencanaPenarikanDana.on('input', function() {
        validateRencanaPenarikanDana();
    });

    // --- Validasi client-side saat form disubmit ---
    // Validasi server-side di simpanRpd adalah yang utama dan akurat.
    // Validasi client-side di sini hanya untuk indikasi awal.
    // Jika Anda ingin membatalkan submit di client-side, aktifkan kembali kode di bawah:
    // $('#rpdForm').submit(function(e) {
    //     if (!validateRencanaPenarikanDana()) {
    //         e.preventDefault(); // Batalkan submit jika validasi client-side gagal
    //         alert('Validasi gagal. Periksa input Anda.'); // Beri peringatan
    //     }
    //     // Validasi server-side akan berjalan setelah ini jika client-side tidak dibatalkan
    // });


    // Initial call if any dropdowns have pre-selected values (e.g., from set_value on validation error)
    // Check if detil_id, tahun, and bulan already have values on page load
    if ($detilId.val() && $tahun.val() && $bulan.val()) {
         // If all are selected, trigger the fetch
         fetchRpdDataAndAvailablePagu();
    } else if ($detilId.val() && $tahun.val()) {
        // If detil and tahun are selected, just fetch available pagu
         fetchRpdDataAndAvailablePagu();
    }
});
</script>
<?= $this->endSection(); ?>
