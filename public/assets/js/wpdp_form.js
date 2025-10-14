document.addEventListener('DOMContentLoaded', function () {
    console.log('WPDP Form JS loaded');
    
    const form = document.querySelector('form');
    if (!form) {
        console.error('Form element not found!');
        return;
    }

    const baseUrl = form.dataset.baseurl;
    const isEditingExisting = form.dataset.isEditingExisting === 'true';
    const isCreatingFromRo = form.dataset.isCreatingFromRo === 'true';
    const programSelect = document.getElementById('program_id');
    const kegiatanSelect = document.getElementById('kegiatan_id');
    const kroSelect = document.getElementById('kro_id');
    const roSelect = document.getElementById('ro_id');
    const komponenSelect = document.getElementById('komponen_id');
    const akunSelect = document.getElementById('akun_id');
    const detilSelect = document.getElementById('detil_id');
    
    console.log('DOM Elements found:', {
        programSelect: !!programSelect,
        kegiatanSelect: !!kegiatanSelect,
        kroSelect: !!kroSelect,
        roSelect: !!roSelect,
        komponenSelect: !!komponenSelect,
        akunSelect: !!akunSelect,
        detilSelect: !!detilSelect
    });
    const bulanSelect = document.getElementById('bulan');
    const usulanWpInput = document.getElementById('usulan_wp');
    const realisasiDpInput = document.getElementById('realisasi_dp');
    const anggaranTersediaDisplay = document.getElementById('anggaran_tersedia_display');
    //const anggaranInput = document.getElementById('usulan_wp');
    //const submitButton = form.querySelector('button[type="submit"]');
    const submitButton = form.querySelector('button[type="submit"]');
 
    // Hardcode Tahun Anggaran
    const tahunAnggaran = 2025;


    // --- Fungsi Sederhana untuk Memuat Opsi Dropdown ---
    async function loadOptions(parentId, targetSelect, urlSegment, selectedId = null) {
        if (!parentId || !targetSelect) {
            return;
        }
        
        console.log(`Loading ${urlSegment} for parent ID: ${parentId}`);
        
        // Ensure targetSelect is not null before proceeding
        if (!targetSelect) {
            console.error(`loadOptions called with null targetSelect for urlSegment: ${urlSegment}`);
            return;
        }
        
        // Jangan reset dropdown jika disabled (di mode edit/createFromRo)
        if (!((isEditingExisting || isCreatingFromRo) && targetSelect.disabled)) {
            targetSelect.innerHTML = '<option value="">-- Pilih ' + targetSelect.previousElementSibling.innerText + ' --</option>';
        }

        // Identifikasi dan reset dropdown berikutnya dalam rantai (Hanya jika dropdown target TIDAK disabled)
        if (!((isEditingExisting || isCreatingFromRo) && targetSelect.disabled)) {
            let currentSelect = targetSelect;
            let nextSelect = null;

            switch (currentSelect.id) {
                case 'program_id': nextSelect = kegiatanSelect; break;
                case 'kegiatan_id': nextSelect = kroSelect; break;
                case 'kro_id': nextSelect = roSelect; break;
                case 'ro_id': nextSelect = komponenSelect; break;
                case 'komponen_id': nextSelect = akunSelect; break;
                case 'akun_id': nextSelect = detilSelect; break;
                case 'detil_id': nextSelect = null; break;
            }

            while (nextSelect) {
                nextSelect.innerHTML = '<option value="">-- Pilih ' + nextSelect.previousElementSibling.innerText + ' --</option>';
                let tempSelect = nextSelect;
                switch (tempSelect.id) {
                    case 'program_id': nextSelect = kegiatanSelect; break;
                    case 'kegiatan_id': nextSelect = kroSelect; break;
                    case 'kro_id': nextSelect = roSelect; break;
                    case 'ro_id': nextSelect = komponenSelect; break;
                    case 'komponen_id': nextSelect = akunSelect; break;
                    case 'akun_id': nextSelect = detilSelect; break;
                    case 'detil_id': nextSelect = null; break;
                }
            }
        }


        if (!parentId) {
            if (!((isEditingExisting || isCreatingFromRo) && targetSelect.disabled)) {
                targetSelect.value = '';
            }
            return; // Jangan fetch jika parentId kosong
        }

        // Hanya fetch jika dropdown target TIDAK disabled
        if (!((isEditingExisting || isCreatingFromRo) && targetSelect.disabled)) {
            try {
                // Asumsi endpoint AJAX ada di base_url/ajaxcontroller/...
                const url = `${baseUrl}ajaxcontroller/${urlSegment}/${parentId}`;
                console.log('Fetching URL:', url);
                
                const response = await fetch(url);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    targetSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Data received:', data);
                
                targetSelect.innerHTML = '<option value="">-- Pilih ' + targetSelect.previousElementSibling.innerText + ' --</option>'; // Reset lagi setelah sukses fetch
                console.log('Resetting targetSelect, now has options:', targetSelect.options.length);
                
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        if (urlSegment === 'kegiatan') {
                            option.textContent = `${item.kode_kegiatan} - ${item.nama_kegiatan}`;
                        } else if (urlSegment === 'kro') {
                            option.textContent = `${item.kode_kro} - ${item.nama_kro}`;
                        } else if (urlSegment === 'ro') {
                            option.textContent = `${item.kode_ro} - ${item.nama_ro}`;
                        } else if (urlSegment === 'komponen') {
                            option.textContent = `${item.kode_komponen} - ${item.nama_komponen}`;
                        } else if (urlSegment === 'akun') {
                            option.textContent = `${item.kode_akun} - ${item.nama_akun}`;
                        } else if (urlSegment === 'detil') {
                            option.textContent = `${item.kode_detil} - ${item.nama_detil}`;
                        }
                        targetSelect.appendChild(option);
                        console.log('Added option:', option.textContent);
                    });
                    console.log('Total options after loading:', targetSelect.options.length);
                } else {
                    console.warn('No data received or data is not an array:', data);
                    targetSelect.innerHTML += '<option value="">-- Tidak ada data --</option>';
                }

                if (selectedId) {
                    targetSelect.value = selectedId;
                    console.log('Set selectedId:', selectedId);
                }


            } catch (error) {
                console.error(`Error loading options for ${targetSelect.id}:`, error);
                targetSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
            }
        } else {
            // Jika disabled, set nilai dari selectedId jika ada (sudah ditangani di view PHP)
            if (selectedId) {
                targetSelect.value = selectedId;
            }
        }
    }


    // --- Fungsi untuk Memuat Nilai Anggaran (Pagu) ---
    async function loadAnggaranValue() {
        // --- Debugging Null Check ---
        if (!detilSelect) {
            console.error('loadAnggaranValue: detilSelect is null. Cannot get value.');
            if (anggaranTersediaDisplay) anggaranTersediaDisplay.value = ''; // Kosongkan display pagu
            return;
        }
        if (!bulanSelect) {
            console.error('loadAnggaranValue: bulanSelect is null. Cannot get value.');
            if (anggaranTersediaDisplay) anggaranTersediaDisplay.value = ''; // Kosongkan display pagu
            return;
        }
        if (!anggaranTersediaDisplay) { // Cek elemen display pagu
            console.error('loadAnggaranValue: anggaranTersediaDisplay is null. Cannot set value.');
            // Tidak return di sini karena masih bisa mengisi usulanWpInput jika perlu (meskipun tidak relevan sekarang)
        }
        // --- End Debugging Null Ch


        const detilId = detilSelect.value;
        const bulan = bulanSelect.value;
        const tahun = tahunAnggaran; // Gunakan tahun hardcode 2025

        // Hanya panggil AJAX jika Detil dan Bulan sudah dipilih (memiliki value)
        if (detilId && bulan) {
            try {
                // Sesuaikan URL endpoint AJAX baru (tidak perlu parameter tahun)
                const url = `${baseUrl}wpdp/getAnggaranByDetilMonthYear/${detilId}/${bulan}`;
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    // Jika gagal, kosongkan field Anggaran
                    if (anggaranTersediaDisplay) anggaranTersediaDisplay.value = ''; // Kosongkan display
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                // --- BAGIAN PENTING: Mengisi nilai elemen display Pagu Tersedia ---
                if (anggaranTersediaDisplay) {
                    // Isi nilai ke elemen display pagu
                    anggaranTersediaDisplay.value = data.anggaran; // Mengisi value karena ini input disabled
                    // Format nilai setelah diisi di elemen display
                    if (anggaranTersediaDisplay.value) {
                        anggaranTersediaDisplay.value = formatRupiah(anggaranTersediaDisplay.value);
                    }
                }
                // --- AKHIR BAGIAN PENTING ---

            } catch (error) {
                console.error('Error loading anggaran value:', error);
                if (anggaranTersediaDisplay) anggaranTersediaDisplay.value = ''; // Kosongkan display jika error
            }
        } else {
            // Jika Detil atau Bulan belum lengkap (value kosong), kosongkan elemen display pagu
            if (anggaranTersediaDisplay) anggaranTersediaDisplay.value = '';
        }
    }


    // --- Event Listeners untuk Dropdown Hierarki (Hanya Aktif jika BUKAN Edit Mode Eksisting ATAU Create From RO) ---
    console.log('Setting up event listeners, conditions:', {
        isEditingExisting: isEditingExisting,
        isCreatingFromRo: isCreatingFromRo,
        shouldSetupListeners: (!isEditingExisting && !isCreatingFromRo)
    });
    
    if (!isEditingExisting && !isCreatingFromRo) { // Aktif hanya di mode tambah baru (bukan dari RO)
        console.log('Setting up program change listener');
        if (programSelect) {
            programSelect.addEventListener('change', function () {
                console.log('Program changed to:', this.value);
                loadOptions(this.value, kegiatanSelect, 'kegiatan');
                loadAnggaranValue(); // Panggil loadAnggaranValue setelah reset dropdown
            });
        } else {
            console.error('programSelect not found');
        }

        if (kegiatanSelect) kegiatanSelect.addEventListener('change', function () {
            loadOptions(this.value, kroSelect, 'kro');
            loadAnggaranValue(); // Panggil loadAnggaranValue setelah reset dropdown
        });

        if (kroSelect) kroSelect.addEventListener('change', function () {
            loadOptions(this.value, roSelect, 'ro');
            loadAnggaranValue(); // Panggil loadAnggaranValue setelah reset dropdown
        });

        if (roSelect) roSelect.addEventListener('change', function () {
            console.log('=== RO CHANGE EVENT (Normal Mode) ===');
            console.log('RO selected:', this.value);
            alert(`RO changed to: ${this.value}`);
            console.log('komponenSelect exists:', !!komponenSelect);
            if (komponenSelect) {
                console.log('Loading komponen options...');
                alert('Loading komponen options...');
                loadOptions(this.value, komponenSelect, 'komponen');
            } else {
                console.error('komponenSelect not found!');
                alert('ERROR: komponenSelect not found!');
            }
            loadAnggaranValue(); // Panggil loadAnggaranValue setelah reset dropdown
        });

        if (komponenSelect) komponenSelect.addEventListener('change', function () {
            loadOptions(this.value, akunSelect, 'akun');
            loadAnggaranValue(); // Panggil loadAnggaranValue setelah reset dropdown
        });

        if (akunSelect) akunSelect.addEventListener('change', function () {
            loadOptions(this.value, detilSelect, 'detil');
            loadAnggaranValue(); // Panggil loadAnggaranValue setelah Detil dimuat
        });
    }
    
    // --- Event Listeners untuk Komponen, Akun, Detil (Aktif di SEMUA Mode) ---
    // Pastikan dropdown komponen selalu memiliki event listener
    if (komponenSelect && !komponenSelect.dataset.hasChangeListener) {
        komponenSelect.addEventListener('change', function () {
            console.log('Komponen changed to:', this.value);
            loadOptions(this.value, akunSelect, 'akun');
            loadAnggaranValue();
        });
        komponenSelect.dataset.hasChangeListener = 'true';
        console.log('Added change listener for komponenSelect (global)');
    }

    if (akunSelect && !akunSelect.dataset.hasChangeListener) {
        akunSelect.addEventListener('change', function () {
            console.log('Akun changed to:', this.value);
            loadOptions(this.value, detilSelect, 'detil');
            loadAnggaranValue();
        });
        akunSelect.dataset.hasChangeListener = 'true';
        console.log('Added change listener for akunSelect (global)');
    }
    
    // Event listener untuk RO sudah ditangani di blok sebelumnya untuk masing-masing mode
    
    // --- Event Listener khusus untuk RO di mode Edit dan CreateFromRo ---
    if ((isEditingExisting || isCreatingFromRo) && roSelect && !roSelect.dataset.hasRoListener) {
        roSelect.addEventListener('change', function () {
            console.log('=== RO CHANGE EVENT (Edit/CreateFromRo Mode) ===');
            console.log('RO selected:', this.value);
            alert(`RO changed (Edit/CreateFromRo): ${this.value}`);
            console.log('komponenSelect exists:', !!komponenSelect);
            if (komponenSelect) {
                console.log('Loading komponen options...');
                alert('Loading komponen options...');
                loadOptions(this.value, komponenSelect, 'komponen');
            } else {
                console.error('komponenSelect not found!');
                alert('ERROR: komponenSelect not found!');
            }
            loadAnggaranValue();
        });
        roSelect.dataset.hasRoListener = 'true';
        console.log('Added RO change listener for edit/createFromRo mode');
    }
    
    if (isCreatingFromRo) {
        // Mode Create From RO: Dropdown Program s/d RO disabled, Komponen s/d Detil aktif
        console.log('Mode: Creating From RO - komponen dropdown should be active');
        // Event listeners sudah dipasang di blok global di atas
    }


    // --- Event Listeners untuk Detil dan Bulan (untuk memuat nilai Anggaran) ---
    // Ini aktif di semua mode (tambah, edit, dan createFromRo)
    // Tambahkan listener hanya jika elemennya ada dan belum ada listener
    // Menggunakan data attribute untuk menandai apakah listener sudah ditambahkan
    if (detilSelect && !detilSelect.dataset.hasChangeListener) {
        detilSelect.addEventListener('change', loadAnggaranValue);
        detilSelect.dataset.hasChangeListener = 'true'; // Mark element
    } else if (detilSelect) {
        console.log('detilSelect already has a change listener');
    } else {
        console.error('detilSelect element not found for adding change listener');
    }

    if (bulanSelect && !bulanSelect.dataset.hasChangeListener) {
        bulanSelect.addEventListener('change', loadAnggaranValue);
        bulanSelect.dataset.hasChangeListener = 'true'; // Mark element
        console.log('Added change listener for bulanSelect');
    } else if (bulanSelect) {
        console.log('bulanSelect already has a change listener');
    } else {
        console.error('bulanSelect element not found for adding change listener');
    }


    // Event listeners untuk mode edit sudah ditangani di blok global di atas


    // --- Logika Saat Halaman Dimuat (Handling Edit Mode Eksisting atau Pre-fill) ---
    const initialProgramId = form.dataset.selectedProgram;
    const initialKegiatanId = form.dataset.selectedKegiatan;
    const initialKroId = form.dataset.selectedKro;
    const initialRoId = form.dataset.selectedRo;
    const initialKomponenId = form.dataset.selectedKomponen;
    const initialAkunId = form.dataset.selectedAkun;
    const initialDetilId = form.dataset.selectedDetil;
    const initialBulan = form.dataset.selectedBulan;
    //const initialAnggaran = form.dataset.initialAnggaran;

    console.log('Initial Data Attributes:', {
        initialProgramId, initialKegiatanId, initialKroId, initialRoId,
        initialKomponenId, initialAkunId, initialDetilId, initialBulan
    });


    if (isEditingExisting) {
        //console.log('Mode: Editing Existing');
        // Mode Edit: Load all hierarchy levels and pre-select
        // Program s/d RO are disabled and pre-selected by PHP
        // Komponen, Akun, Detil are not disabled, need to load options and pre-select
        // Pastikan initialRoId ada sebelum memuat komponen
        if (initialRoId) {
            loadOptions(initialRoId, komponenSelect, 'komponen', initialKomponenId).then(() => {
                if (initialKomponenId) {
                    return loadOptions(initialKomponenId, akunSelect, 'akun', initialAkunId);
                }
            }).then(() => {
                if (initialAkunId) {
                    return loadOptions(initialAkunId, detilSelect, 'detil', initialDetilId);
                }
            })
                .then(() => {
                    // Setelah semua dropdown hierarki (s/d Detil) dimuat dan dipilih,
                    // panggil loadAnggaranValue jika Detil dan Bulan sudah ada nilainya.
                    if (initialDetilId && initialBulan) {
                        console.log('Initial load complete in Edit mode. Calling loadAnggaranValue.');
                        loadAnggaranValue();
                    }
                })
                .catch(error => console.error('Error during initial hierarchy load (Edit Mode):', error));
        } else {
            console.warn('initialRoId is missing in Edit mode. Cannot load Komponen.');
        }
        if (usulanWpInput && usulanWpInput.value) {
            usulanWpInput.value = formatRupiah(usulanWpInput.value);
        }
        if (realisasiDpInput && realisasiDpInput.value) {
            realisasiDpInput.value = formatRupiah(realisasiDpInput.value);
        }

        // Initial anggaran sudah di-set oleh PHP di view, tidak perlu loadAnggaranValue di sini

    } else if (isCreatingFromRo) {
        console.log('Mode: Creating From RO');
        // Mode Create From RO: Program s/d RO are disabled and pre-selected by PHP
        // Komponen, Akun, Detil are not disabled, need to load options based on selected RO
        if (initialRoId) {
            loadOptions(initialRoId, komponenSelect, 'komponen')
                .catch(error => console.error('Error during initial Komponen load (Create From RO Mode):', error));

            // Set nilai dropdown Program s/d RO dari data attribute (diperlukan karena disabled)
            // Ini sudah ditangani di view PHP, tapi bisa juga di JS sebagai fallback
            if (programSelect && initialProgramId) programSelect.value = initialProgramId;
            if (kegiatanSelect && initialKegiatanId) kegiatanSelect.value = initialKegiatanId;
            if (kroSelect && initialKroId) kroSelect.value = initialKroId;
            if (roSelect && initialRoId) roSelect.value = initialRoId;

            // loadAnggaranValue akan dipicu oleh event listener 'change' pada detilSelect dan bulanSelect
            // setelah pengguna memilih Detil dan Bulan.
        } else {
            console.error('initialRoId is missing in Create From RO mode');
        }

    } else {
        console.log('Mode: Adding New (Not from RO)');
        // Mode Tambah Baru (bukan dari RO): Event listeners handle everything starting from Program change.
        // No initial load needed here other than the initial Program list from PHP.
    }


    // --- Logika Tambah/Hapus Kendala & Solusi ---
    const kendalaContainer = document.getElementById('kendala-container');
    const addKendalaButton = document.getElementById('add-kendala');
    const solusiContainer = document.getElementById('solusi-container');
    const addSolusiButton = document.getElementById('add-solusi');

    //console.log('Kendala Container:', kendalaContainer);
    //console.log('Add Kendala Button:', addKendalaButton);
    //console.log('Solusi Container:', solusiContainer);
    //console.log('Add Solusi Button:', addSolusiButton);

    function validateUsulanWp() {
            const paguTersedia = parseInt(anggaranTersediaDisplay.value.replace(/\./g, '').replace(/,/g, '')) || 0;
            const usulanWp = parseInt(usulanWpInput.value.replace(/\./g, '').replace(/,/g, '')) || 0;

            if (usulanWp > paguTersedia) {
                alert('Usulan WP tidak boleh lebih besar dari Pagu Tersedia.');
                usulanWpInput.value = ''; // Kosongkan input
                return false;
            }
            return true;
        }
                // Event listener untuk validasi saat "Usulan WP" diubah
        usulanWpInput.addEventListener('input', validateUsulanWp);
    
    function validateDetilSelection() {
            const selectedDetil = detilSelect.options[detilSelect.selectedIndex].text.toLowerCase(); // Konversi ke huruf kecil
            if (selectedDetil.includes('efisiensi')) {
                usulanWpInput.value = ''; // Kosongkan input
                usulanWpInput.disabled = true; // Nonaktifkan input
                alert('Anda tidak bisa mengisi Usulan WP jika Detil "Efisiensi Anggaran".');
            } else {
                usulanWpInput.disabled = false; // Aktifkan input
            }
        }
    detilSelect.addEventListener('change', validateDetilSelection);
    
    
    function handleRemoveButtonClick(event) {
        const button = event.target;
        const inputGroup = button.closest('.input-group');
        const container = inputGroup.parentElement;

        if (container.children.length > 1) {
            inputGroup.remove();
        } else {
            inputGroup.querySelector('input[type="text"]').value = '';
        }
    }

    function createInputGroup(name, placeholder = '') {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="text" name="${name}[]" class="form-control" placeholder="${placeholder}">
            <button type="button" class="btn btn-danger remove-${name}">Hapus</button>
        `;
        const removeButton = div.querySelector(`.remove-${name}`);
        if (removeButton) {
            removeButton.addEventListener('click', handleRemoveButtonClick);
            console.log(`Added remove listener for ${name}`);
        } else {
            console.error(`Remove button not found for ${name}`);
        }
        return div;
    }

    if (addKendalaButton && kendalaContainer) {
        addKendalaButton.addEventListener('click', function () {
            console.log('Add Kendala button clicked');
            kendalaContainer.appendChild(createInputGroup('kendala', 'Masukkan kendala'));
        });
        console.log('Add listener for Add Kendala button');
    } else {
        console.log('Add Kendala button or container not found');
    }

    if (addSolusiButton && solusiContainer) {
        addSolusiButton.addEventListener('click', function () {
            console.log('Add Solusi button clicked');
            solusiContainer.appendChild(createInputGroup('solusi', 'Masukkan solusi'));
        });
        console.log('Add listener for Add Solusi button');
    } else {
        console.log('Add Solusi button or container not found');
    }

    // Add listeners to existing remove buttons on load
    if (kendalaContainer) {
        kendalaContainer.querySelectorAll('.remove-kendala').forEach(button => {
            button.addEventListener('click', handleRemoveButtonClick);
            console.log('Added remove listener for existing kendala');
        });
    }

    if (solusiContainer) {
        solusiContainer.querySelectorAll('.remove-solusi').forEach(button => {
            button.addEventListener('click', handleRemoveButtonClick);
            console.log('Added remove listener for existing solusi');
        });
    }


    // --- Logika Format Angka Ribuan & Bersihkan Saat Submit ---
    function formatRupiah(angka) {
        if (angka === null || angka === undefined || angka === '') return ''; // Handle null/empty
        let number_string = angka.replace(/[^,\d]/g, '').toString();
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    if (usulanWpInput) {
        usulanWpInput.addEventListener('input', function (e) {
            const start = this.selectionStart;
            const oldLength = this.value.length;
            this.value = formatRupiah(this.value);
            const newLength = this.value.length;
            this.selectionStart = this.selectionEnd = start + (newLength - oldLength);
        });
        // Format nilai awal saat dimuat
        if (usulanWpInput.value) {
            usulanWpInput.value = formatRupiah(usulanWpInput.value);
        }
    }

    if (realisasiDpInput) {
        realisasiDpInput.addEventListener('input', function (e) {
            const start = this.selectionStart;
            const oldLength = this.value.length;
            this.value = formatRupiah(this.value);
            const newLength = this.value.length;
            this.selectionStart = this.selectionEnd = start + (newLength - oldLength);
        });
        // Format nilai awal saat dimuat
        if (realisasiDpInput.value) {
            realisasiDpInput.value = formatRupiah(realisasiDpInput.value);
        }
    }


    if (form) {
        form.addEventListener('submit', function (event) { // Tangkap event
            // Mencegah submit default browser
            event.preventDefault();
            
            // Bersihkan format angka
            if (usulanWpInput) {
                usulanWpInput.value = usulanWpInput.value.replace(/\./g, '').replace(/,/g, '');
            }
            if (realisasiDpInput) {
                realisasiDpInput.value = realisasiDpInput.value.replace(/\./g, '').replace(/,/g, '');
            }
            
            // Opsional: Nonaktifkan tombol submit untuk mencegah klik ganda
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...'; // Ubah teks tombol
            }

            // Manual submit form setelah pembersihan
            form.submit();
        });
    }
});