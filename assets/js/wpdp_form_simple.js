document.addEventListener('DOMContentLoaded', function () {
    console.log('WPDP Form JS Loaded (Simple Version)');
    
    const form = document.querySelector('form');
    if (!form) return;

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
    const bulanSelect = document.getElementById('bulan');
    const usulanWpInput = document.getElementById('usulan_wp');
    const realisasiDpInput = document.getElementById('realisasi_dp');
    const anggaranTersediaDisplay = document.getElementById('anggaran_tersedia_display');

    // Hardcode Tahun Anggaran
    const tahunAnggaran = 2025;

    // Simple load options function
    async function loadOptions(parentId, targetSelect, urlSegment, selectedId = null) {
        if (!parentId || !targetSelect) return;
        
        console.log(`Loading ${urlSegment} for parent ${parentId}`);
        
        // Reset target dropdown
        targetSelect.innerHTML = '<option value="">-- Pilih ' + targetSelect.previousElementSibling.innerText + ' --</option>';
        
        // Reset subsequent dropdowns
        if (targetSelect.id === 'komponen_id') {
            if (akunSelect) akunSelect.innerHTML = '<option value="">-- Pilih Akun --</option>';
            if (detilSelect) detilSelect.innerHTML = '<option value="">-- Pilih Detil --</option>';
        } else if (targetSelect.id === 'akun_id') {
            if (detilSelect) detilSelect.innerHTML = '<option value="">-- Pilih Detil --</option>';
        }

        try {
            const url = `${baseUrl}ajaxcontroller/${urlSegment}/${parentId}`;
            console.log('Fetching:', url);
            
            const response = await fetch(url);
            if (!response.ok) {
                targetSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                return;
            }
            
            const data = await response.json();
            
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
                });
                
                console.log(`Successfully loaded ${data.length} ${urlSegment} options`);
            } else {
                targetSelect.innerHTML += '<option value="">-- Tidak ada data --</option>';
            }
            
            if (selectedId) {
                targetSelect.value = selectedId;
            }
            
        } catch (error) {
            console.error(`Error loading ${urlSegment}:`, error);
            targetSelect.innerHTML = '<option value="">-- Error loading data --</option>';
        }
    }

    // Load anggaran function
    async function loadAnggaranValue() {
        if (!detilSelect || !bulanSelect || !anggaranTersediaDisplay) return;

        const detilId = detilSelect.value;
        const bulan = bulanSelect.value;

        if (detilId && bulan) {
            try {
                const url = `${baseUrl}wpdp/getAnggaranByDetilMonthYear/${detilId}/${bulan}`;
                const response = await fetch(url);
                
                if (response.ok) {
                    const data = await response.json();
                    anggaranTersediaDisplay.value = data.anggaran || '';
                    if (anggaranTersediaDisplay.value) {
                        anggaranTersediaDisplay.value = formatRupiah(anggaranTersediaDisplay.value);
                    }
                }
            } catch (error) {
                console.error('Error loading anggaran value:', error);
            }
        } else {
            anggaranTersediaDisplay.value = '';
        }
    }

    // Format rupiah function
    function formatRupiah(value) {
        const numericValue = value.toString().replace(/[^\d]/g, '');
        if (!numericValue) return '';
        return new Intl.NumberFormat('id-ID').format(numericValue);
    }

    // Event listeners - SIMPLIFIED
    if (programSelect && !isEditingExisting && !isCreatingFromRo) {
        programSelect.addEventListener('change', function () {
            console.log('Program changed:', this.value);
            loadOptions(this.value, kegiatanSelect, 'kegiatan');
        });
    }

    if (kegiatanSelect && !isEditingExisting && !isCreatingFromRo) {
        kegiatanSelect.addEventListener('change', function () {
            console.log('Kegiatan changed:', this.value);
            loadOptions(this.value, kroSelect, 'kro');
        });
    }

    if (kroSelect && !isEditingExisting && !isCreatingFromRo) {
        kroSelect.addEventListener('change', function () {
            console.log('KRO changed:', this.value);
            loadOptions(this.value, roSelect, 'ro');
        });
    }

    // RO change listener - UNTUK SEMUA MODE
    if (roSelect) {
        roSelect.addEventListener('change', function () {
            console.log('=== RO CHANGED ===');
            console.log('RO value:', this.value);
            console.log('komponenSelect exists:', !!komponenSelect);
            
            if (this.value && komponenSelect) {
                console.log('Loading komponen...');
                loadOptions(this.value, komponenSelect, 'komponen');
            }
        });
        console.log('RO event listener added');
    }

    // Komponen change listener - UNTUK SEMUA MODE
    if (komponenSelect) {
        komponenSelect.addEventListener('change', function () {
            console.log('Komponen changed:', this.value);
            if (this.value && akunSelect) {
                loadOptions(this.value, akunSelect, 'akun');
            }
        });
    }

    // Akun change listener - UNTUK SEMUA MODE
    if (akunSelect) {
        akunSelect.addEventListener('change', function () {
            console.log('Akun changed:', this.value);
            if (this.value && detilSelect) {
                loadOptions(this.value, detilSelect, 'detil');
            }
        });
    }

    // Detil and Bulan listeners
    if (detilSelect) {
        detilSelect.addEventListener('change', loadAnggaranValue);
    }

    if (bulanSelect) {
        bulanSelect.addEventListener('change', loadAnggaranValue);
    }

    // Format input values
    if (usulanWpInput && usulanWpInput.value) {
        usulanWpInput.value = formatRupiah(usulanWpInput.value);
    }
    if (realisasiDpInput && realisasiDpInput.value) {
        realisasiDpInput.value = formatRupiah(realisasiDpInput.value);
    }

    // Input formatting
    if (usulanWpInput) {
        usulanWpInput.addEventListener('input', function() {
            this.value = formatRupiah(this.value);
        });
    }
    if (realisasiDpInput) {
        realisasiDpInput.addEventListener('input', function() {
            this.value = formatRupiah(this.value);
        });
    }

    // Validation functions
    function validateUsulanWp() {
        if (!anggaranTersediaDisplay || !usulanWpInput) return true;
        
        const paguTersedia = parseInt(anggaranTersediaDisplay.value.replace(/\./g, '').replace(/,/g, '')) || 0;
        const usulanWp = parseInt(usulanWpInput.value.replace(/\./g, '').replace(/,/g, '')) || 0;

        if (usulanWp > paguTersedia) {
            alert('Usulan WP tidak boleh lebih besar dari Pagu Tersedia.');
            usulanWpInput.value = '';
            return false;
        }
        return true;
    }

    function validateDetilSelection() {
        if (!detilSelect || !usulanWpInput) return;
        
        const selectedDetil = detilSelect.options[detilSelect.selectedIndex].text.toLowerCase();
        if (selectedDetil.includes('efisiensi')) {
            usulanWpInput.value = '';
            usulanWpInput.disabled = true;
            alert('Anda tidak bisa mengisi Usulan WP jika Detil "Efisiensi Anggaran".');
        } else {
            usulanWpInput.disabled = false;
        }
    }

    if (usulanWpInput) {
        usulanWpInput.addEventListener('input', validateUsulanWp);
    }
    if (detilSelect) {
        detilSelect.addEventListener('change', validateDetilSelection);
    }

    // Initial load for edit mode
    const initialProgramId = form.dataset.selectedProgram;
    const initialKegiatanId = form.dataset.selectedKegiatan;
    const initialKroId = form.dataset.selectedKro;
    const initialRoId = form.dataset.selectedRo;
    const initialKomponenId = form.dataset.selectedKomponen;
    const initialAkunId = form.dataset.selectedAkun;
    const initialDetilId = form.dataset.selectedDetil;

    if (isEditingExisting && initialRoId) {
        console.log('Edit mode - loading initial data...');
        loadOptions(initialRoId, komponenSelect, 'komponen', initialKomponenId).then(() => {
            if (initialKomponenId) {
                return loadOptions(initialKomponenId, akunSelect, 'akun', initialAkunId);
            }
        }).then(() => {
            if (initialAkunId) {
                return loadOptions(initialAkunId, detilSelect, 'detil', initialDetilId);
            }
        }).then(() => {
            loadAnggaranValue();
        });
    } else if (isCreatingFromRo && initialRoId) {
        console.log('CreateFromRo mode - loading komponen...');
        loadOptions(initialRoId, komponenSelect, 'komponen');
    }

    // Kendala & Solusi management
    const kendalaContainer = document.getElementById('kendala-container');
    const addKendalaButton = document.getElementById('add-kendala');
    const solusiContainer = document.getElementById('solusi-container');
    const addSolusiButton = document.getElementById('add-solusi');

    function handleRemoveButtonClick(event) {
        const button = event.target;
        if (button.classList.contains('remove-kendala') || button.classList.contains('remove-solusi')) {
            const container = button.closest('.input-group');
            const parentContainer = container.parentNode;
            
            if (parentContainer.children.length > 1) {
                container.remove();
            } else {
                const input = container.querySelector('input');
                input.value = '';
            }
        }
    }

    if (kendalaContainer) {
        kendalaContainer.addEventListener('click', handleRemoveButtonClick);
    }
    if (solusiContainer) {
        solusiContainer.addEventListener('click', handleRemoveButtonClick);
    }

    if (addKendalaButton && kendalaContainer) {
        addKendalaButton.addEventListener('click', function() {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = `
                <input type="text" name="kendala[]" class="form-control" placeholder="Masukkan kendala">
                <button type="button" class="btn btn-danger remove-kendala">Hapus</button>
            `;
            kendalaContainer.appendChild(inputGroup);
        });
    }

    if (addSolusiButton && solusiContainer) {
        addSolusiButton.addEventListener('click', function() {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = `
                <input type="text" name="solusi[]" class="form-control" placeholder="Masukkan solusi">
                <button type="button" class="btn btn-danger remove-solusi">Hapus</button>
            `;
            solusiContainer.appendChild(inputGroup);
        });
    }

    // Form submission formatting
    if (form) {
        form.addEventListener('submit', function(event) {
            // Convert formatted rupiah back to numbers
            if (usulanWpInput) {
                const numericValue = usulanWpInput.value.replace(/\./g, '').replace(/,/g, '');
                usulanWpInput.value = numericValue;
            }
            if (realisasiDpInput) {
                const numericValue = realisasiDpInput.value.replace(/\./g, '').replace(/,/g, '');
                realisasiDpInput.value = numericValue;
            }
        });
    }

    console.log('WPDP Form JS Initialization Complete');
});