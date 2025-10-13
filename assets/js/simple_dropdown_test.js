document.addEventListener('DOMContentLoaded', function () {
    console.log('Simple test script loaded');
    
    const programSelect = document.getElementById('program_id');
    const kegiatanSelect = document.getElementById('kegiatan_id');
    
    console.log('Elements:', {
        programSelect: !!programSelect,
        kegiatanSelect: !!kegiatanSelect
    });
    
    if (programSelect) {
        programSelect.addEventListener('change', async function() {
            const programId = this.value;
            console.log('Program changed to:', programId);
            
            if (!programId) {
                kegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';
                return;
            }
            
            try {
                const response = await fetch(`http://localhost:8080/ajaxcontroller/kegiatan/${programId}`);
                const data = await response.json();
                console.log('Kegiatan data:', data);
                
                kegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';
                
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.kode_kegiatan} - ${item.nama_kegiatan}`;
                    kegiatanSelect.appendChild(option);
                });
                
                console.log('Kegiatan dropdown updated with', data.length, 'items');
            } catch (error) {
                console.error('Error loading kegiatan:', error);
            }
        });
    }
});