<form action="/wpdp/save" method="post" enctype="multipart/form-data">
    <label>Bulan:</label>
    <select name="bulan" required>
        <option value="">-- Pilih Bulan --</option>
        <?php
        $bulanList = [
            'Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'
        ];
        foreach ($bulanList as $b) {
            echo "<option value=\"$b\">$b</option>";
        }
        ?>
    </select><br><br>

    <label>Nama Survei/Kegiatan:</label>
    <input type="text" name="nama_kegiatan" required><br><br>

    <label>Nama Aktivitas:</label>
    <input type="text" name="nama_aktivitas" required><br><br>

    <label>Usulan Anggaran:</label>
    <input type="number" name="anggaran_usulan" required><br><br>

    <label>Realisasi Anggaran:</label>
    <input type="number" name="anggaran_realisasi" required><br><br>

    <label>Kendala:</label><br>
    <textarea name="kendala" rows="3" cols="50"></textarea><br><br>

    <label>Solusi:</label><br>
    <textarea name="solusi" rows="3" cols="50"></textarea><br><br>

    <label>Memo (Opsional):</label><br>
    <textarea name="memo" rows="5" cols="60"></textarea><br><br>

    <label>Upload File:</label>
    <input type="file" name="file_upload"><br><br>

    <!-- RO terakhir yang dipilih -->
    <input type="hidden" name="ro_id" id="selected_ro_id">

    <button type="submit">Simpan WPDP Bulanan</button>
</form>

<script>
    $('#ro').change(function(){
        $('#selected_ro_id').val($(this).val());
    });
</script>
