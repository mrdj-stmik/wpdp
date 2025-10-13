<?php

namespace App\Models;

use CodeIgniter\Model;

class RpdModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'rpd'; // Nama tabel RPD
    protected $detailTable = 'detil'; // Nama tabel detil
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'detil_id',
        'tahun',
        'bulan',
        'rencana_penarikan_dana',
        'keterangan', // Keterangan di level RPD bulanan
    ];

    // Dates
    protected $useTimestamps = true; // Gunakan timestamps
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Tidak pakai soft delete, tapi biarkan jika default

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Metode untuk mendapatkan data laporan RPD
    public function getRpdReportData($tahun, $searchTerm = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detil d'); // Mulai dari tabel detil
        $builder->select("
            p.nama_program,
            k.nama_kegiatan,
            kr.nama_kro,
            r.kode_ro,
            r.nama_ro,
            ko.kode_komponen,
            ko.nama_komponen,
            a.kode_akun,
            a.nama_akun,
            d.kode_detil,
            d.nama_detil,
            d.pagu_revisi_terakhir AS pagu_revisi,
            d.lock_pagu, --  Asumsi kolom lock_pagu ada di tabel detil
            --  Subquery untuk menghitung Total Realisasi DP per Detil dari WPDP Bulanan
            (SELECT SUM(wb.realisasi_dp) FROM wpdp_bulanan wb WHERE wb.detil_id = d.id AND wb.tahun = {$tahun}) AS total_realisasi_wpdp,
             -- Memivot data usulan_wp (RPD) dari wpdp_bulanan per bulan
            MAX(CASE WHEN wb.bulan = 'Januari' THEN wb.usulan_wp ELSE 0 END) AS januari,
            MAX(CASE WHEN wb.bulan = 'Februari' THEN wb.usulan_wp ELSE 0 END) AS februari,
            MAX(CASE WHEN wb.bulan = 'Maret' THEN wb.usulan_wp ELSE 0 END) AS maret,
            MAX(CASE WHEN wb.bulan = 'April' THEN wb.usulan_wp ELSE 0 END) AS april,
            MAX(CASE WHEN wb.bulan = 'Mei' THEN wb.usulan_wp ELSE 0 END) AS mei,
            MAX(CASE WHEN wb.bulan = 'Juni' THEN wb.usulan_wp ELSE 0 END) AS juni,
            MAX(CASE WHEN wb.bulan = 'Juli' THEN wb.usulan_wp ELSE 0 END) AS juli,
            MAX(CASE WHEN wb.bulan = 'Agustus' THEN wb.usulan_wp ELSE 0 END) AS agustus,
            MAX(CASE WHEN wb.bulan = 'September' THEN wb.usulan_wp ELSE 0 END) AS september,
            MAX(CASE WHEN wb.bulan = 'Oktober' THEN wb.usulan_wp ELSE 0 END) AS oktober,
            MAX(CASE WHEN wb.bulan = 'November' THEN wb.usulan_wp ELSE 0 END) AS november,
            MAX(CASE WHEN wb.bulan = 'Desember' THEN wb.usulan_wp ELSE 0 END) AS desember,
            
            MAX(CASE WHEN wb.bulan = 'Januari' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_januari,
            MAX(CASE WHEN wb.bulan = 'Februari' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_februari,
            MAX(CASE WHEN wb.bulan = 'Maret' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_maret,
            MAX(CASE WHEN wb.bulan = 'April' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_april,
            MAX(CASE WHEN wb.bulan = 'Mei' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_mei,
            MAX(CASE WHEN wb.bulan = 'Juni' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_juni,
            MAX(CASE WHEN wb.bulan = 'Juli' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_juli,
            MAX(CASE WHEN wb.bulan = 'Agustus' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_agustus,
            MAX(CASE WHEN wb.bulan = 'September' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_september,
            MAX(CASE WHEN wb.bulan = 'Oktober' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_oktober,
            MAX(CASE WHEN wb.bulan = 'November' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_november,
            MAX(CASE WHEN wb.bulan = 'Desember' AND wb.realisasi_dp > 0 THEN 1 ELSE 0 END) AS is_realisasi_desember,
            
            -- Subquery untuk menghitung Total usulan_wp (RPD) setahun per Detil
            (SELECT SUM(wb_sum.usulan_wp)
             FROM wpdp_bulanan wb_sum
             WHERE wb_sum.detil_id = d.id AND wb_sum.tahun = {$tahun}) AS jumlah_rpd,
            --  Mengambil keterangan dari salah satu entri RPD (jika ada banyak, ini bisa jadi tidak akurat jika keterangan berbeda per bulan)
            --  Atau mungkin keterangan di laporan adalah agregasi dari keterangan bulanan?
            --  Untuk sederhana, ambil GROUP_CONCAT dari keterangan bulanan
            GROUP_CONCAT(DISTINCT wb.memo SEPARATOR '\n') AS keterangan_rpd_rekap
        ");

        // Join ke atas dari detil
        $builder->join('akun a', 'a.id = d.akun_id');
        $builder->join('komponen ko', 'ko.id = a.komponen_id');
        $builder->join('ro r', 'r.id = ko.ro_id');
        $builder->join('kro kr', 'kr.id = r.kro_id');
        $builder->join('kegiatan k', 'k.id = kr.kegiatan_id');
        $builder->join('program p', 'p.id = k.program_id');

        // LEFT JOIN ke tabel wpdp_bulanan untuk tahun yang dipilih
        // LEFT JOIN penting agar detil yang belum punya WPDP tetap muncul
        // Join berdasarkan detil_id dan tahun
        $builder->join('wpdp_bulanan wb', 'wb.detil_id = d.id AND wb.tahun = ' . $tahun, 'left');

                // --- Menerapkan Search dari Controller ---

        // Filter berdasarkan search term di berbagai kolom jika parameter 'search' ada
        if (!empty($searchTerm)) {
            // Gunakan groupStart/groupEnd untuk mengelompokkan kondisi OR
            $builder->groupStart()
                    ->like('p.nama_program', $searchTerm) // Cari di nama program
                    ->orLike('k.nama_kegiatan', $searchTerm) // Cari di nama kegiatan
                    ->orLike('kr.kode_kro', $searchTerm) // Cari di kode KRO
                    ->orLike('r.kode_ro', $searchTerm) // Cari di kode RO
                    ->orLike('r.nama_ro', $searchTerm) // Cari di nama RO
                    ->orLike('ko.kode_komponen', $searchTerm) // Cari di kode komponen
                    ->orLike('ko.nama_komponen', $searchTerm) // Cari di nama komponen
                    ->orLike('a.kode_akun', $searchTerm) // Cari di kode akun
                    ->orLike('a.nama_akun', $searchTerm) // Cari di nama akun
                    ->orLike('d.kode_detil', $searchTerm)     // Cari di kode detil
                    ->orLike('d.nama_detil', $searchTerm)     // Cari di nama detil
                    ->orLike('wb.memo', $searchTerm) // Cari di kolom memo WPDP
                    ->groupEnd();
        }

       // --- Akhir Menerapkan Search ---

        // Grouping per Detil untuk mendapatkan satu baris per detil di laporan
        $builder->groupBy('d.id, p.nama_program, k.nama_kegiatan, kr.kode_kro, r.kode_ro, r.nama_ro, ko.kode_komponen, ko.nama_komponen, a.kode_akun, a.nama_akun, d.kode_detil, d.nama_detil, d.pagu_revisi_terakhir, d.lock_pagu'); // Update grouping

        // Urutkan berdasarkan hierarki
        $builder->orderBy('p.nama_program, k.nama_kegiatan, kr.kode_kro, r.kode_ro, ko.kode_komponen, a.kode_akun, d.kode_detil');

        $query = $builder->get();
        $results = $query->getResultArray();

        // Hitung Sisa Pagu di PHP setelah mendapatkan hasil query
        // Sisa Pagu = Pagu Revisi Terakhir - Total Realisasi WPDP
        foreach ($results as &$row) {
            $paguRevisi = $row['pagu_revisi'] ?? 0; // Mengambil dari pagu_revisi_terakhir
            $totalRealisasiWpdp = $row['total_realisasi_wpdp'] ?? 0;
            $row['sisa_pagu'] = $paguRevisi - $totalRealisasiWpdp;

            // Format angka untuk tampilan
            $row['pagu_revisi_formatted'] = number_format($paguRevisi, 0, ',', '.');
            $row['total_realisasi_wpdp_formatted'] = number_format($totalRealisasiWpdp, 0, ',', '.');
            $row['sisa_pagu_formatted'] = number_format($row['sisa_pagu'], 0, ',', '.');
            $row['jumlah_rpd_formatted'] = number_format($row['jumlah_rpd'] ?? 0, 0, ',', '.');

            // Format bulanan
            $months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
            foreach($months as $month) {
                 $row[$month . '_formatted'] = number_format($row[$month] ?? 0, 0, ',', '.');
            }

            // Handle NULL keterangan
            $row['keterangan_rpd_rekap'] = $row['keterangan_rpd_rekap'] ?? '-'; // Ganti NULL dengan '-'
        }
        unset($row); // Unset reference

        return $results;
    }

     // Metode untuk mendapatkan tahun-tahun unik dari data RPD
     public function getAvailableYears()
     {
        // Ambil tahun dari tabel wpdp_bulanan
        return $this->db->table('wpdp_bulanan')->select('tahun')->distinct()->orderBy('tahun', 'DESC')->get()->getResultArray();
    }

// Metode untuk menghitung total RPD yang sudah direncanakan untuk detil, tahun, dan bulan-bulan sebelumnya
public function getTotalRpdSebelumnya($detilId, $tahun, $bulanSaatIni)
{
    // Mapping nama bulan ke angka
    $monthMap = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3,
        'April' => 4, 'Mei' => 5, 'Juni' => 6,
        'Juli' => 7, 'Agustus' => 8, 'September' => 9,
        'Oktober' => 10, 'November' => 11, 'Desember' => 12
    ];

    // Jika bulanSaatIni adalah Januari, tidak ada bulan sebelumnya
    if ($bulanSaatIni === 'Januari') {
        return 0;
    }

    // Dapatkan nomor bulan saat ini
    $currentMonthNumber = $monthMap[$bulanSaatIni] ?? 0;
    if ($currentMonthNumber === 0) {
        // Handle error atau return 0 jika nama bulan tidak valid
        return 0;
    }

    // Query untuk menjumlahkan rencana_penarikan_dana untuk bulan-bulan sebelumnya
    $builder = $this->selectSum('rencana_penarikan_dana')
                    ->where('detil_id', $detilId)
                    ->where('tahun', $tahun)
                    ->whereIn('bulan', array_keys(array_filter($monthMap, fn($num) => $num < $currentMonthNumber))); // Filter bulan dengan nomor lebih kecil

    $result = $builder->get()->getRow();

    return $result ? ($result->rencana_penarikan_dana ?? 0) : 0; // Return total atau 0 jika NULL
}

public static function getTotalRealisasi(int $detailId, int $tahun, int $bulanSaatIni): float
    {
        // --- Logika pengambilan data realisasi dari tabel wpdp_bulanan ---
        // Asumsi tabel 'wpdp_bulanan' memiliki kolom:
        // - id
        // - rpd_detail_id (foreign key ke detail RPD, sesuaikan nama kolom jika berbeda)
        // - realisasi_dp (kolom yang menyimpan jumlah realisasi)
        // - tahun (kolom tahun)
        // - bulan (kolom bulan)
        // - status (opsional, jika realisasi perlu status tertentu)

        $totalRealisasi = DB::table('wpdp_bulanan') // Menggunakan tabel 'wpdp_bulanan'
            // Filter berdasarkan detail RPD yang terkait
            // Asumsi nama kolom foreign key adalah 'rpd_detail_id', sesuaikan jika berbeda
            ->where('rpd_detail_id', $detailId)
            // Filter berdasarkan tahun anggaran
            ->where('tahun', $tahun) // Menggunakan kolom 'tahun' di tabel wpdp_bulanan
            // Filter untuk bulan-bulan sebelum bulan saat ini
            ->where('bulan', '<', $bulanSaatIni) // Menggunakan kolom 'bulan' di tabel wpdp_bulanan
            // Opsional: tambahkan filter status jika hanya realisasi dengan status tertentu yang dihitung
            // ->where('status', 'disetujui')
            // Menjumlahkan kolom realisasi_dp
            ->sum('realisasi_dp'); // Menjumlahkan kolom 'realisasi_dp'

        // Mengembalikan total realisasi. Jika tidak ada data, sum() akan mengembalikan 0.0
        return (float) $totalRealisasi;
    }

    public function getTotalPaguAwal($tahun)
    {
        // Lakukan JOIN dari tabel RPD utama ke tabel detail pagu
        $builder = $this->db->table($this->detailTable); // Alias untuk tabel RPD utama
        $builder->selectSum('pagu_anggaran_awal'); // <-- Menjumlahkan kolom 'pagu_awal' dari tabel detail
        //$builder->join($this->detailTable . ' rd', 'r.detil_id = rd.id', 'left'); // JOIN berdasarkan kode_akun
        //$builder->where('r.tahun', $tahun); // Filter berdasarkan tahun di tabel RPD utama

        // Karena data hanya untuk satu tahun (2025) dan asumsi tidak ada duplikasi kode_akun
        // yang menyebabkan over-counting saat join, kita tidak perlu DISTINCT.
        // Jika ada kemungkinan satu kode_akun muncul berkali-kali di tabel RPD utama untuk tahun yang sama
        // dan ini menyebabkan penjumlahan pagu yang salah, pertimbangkan kembali kebutuhan DISTINCT.

        $result = $builder->get()->getRow();

        return $result->pagu_anggaran_awal ?? 0; // Mengembalikan 0 jika tidak ada data
    }

    /**
     * Mengambil total pagu revisi terakhir untuk tahun tertentu.
     * Asumsi: Pagu revisi terakhir disimpan dalam kolom 'nilai_pagu_revisi' pada record RPD.
     * Sama seperti pagu awal, mungkin perlu penyesuaian.
     */
    public function getTotalPaguRevisiTerakhir($tahun)
    {
        // Lakukan JOIN dari tabel RPD utama ke tabel detail pagu
        $builder = $this->db->table($this->detailTable); // Alias untuk tabel RPD utama
        $builder->selectSum('pagu_revisi_terakhir'); // <-- Menjumlahkan kolom 'pagu_revisi_terakhir' dari tabel detail
        //$builder->join($this->detailTable . ' rd', 'r.detil_id = rd.id', 'left'); // JOIN berdasarkan kode_akun
        //$builder->where('r.tahun', $tahun); // Filter berdasarkan tahun di tabel RPD utama

        // Karena data hanya untuk satu tahun (2025) dan asumsi tidak ada duplikasi kode_akun
        // yang menyebabkan over-counting saat join, kita tidak perlu DISTINCT.

        $result = $builder->get()->getRow();

        return $result->pagu_revisi_terakhir ?? 0; // Mengembalikan 0 jika tidak ada data
    }

    /**
     * Mengambil total nilai RPD untuk semua bulan dalam tahun tertentu.
     */
    public function getTotalRpdSetahun($tahun)
    {
        return $this->db->table($this->table)
                        ->selectSum('rencana_penarikan_dana')
                        ->where('tahun', $tahun)
                        ->get()
                        ->getRow()->rencana_penarikan_dana ?? 0; // Mengembalikan 0 jika tidak ada data
                        // Mengembalikan 0 jika tidak ada data
    }

    /**
     * Mengambil data RPD dan Realisasi (dari tabel WPDP) per bulan untuk tahun tertentu.
     * Method ini memerlukan JOIN atau query terpisah ke tabel WPDP.
     * Asumsi: Ada kolom 'bulan' dan 'tahun_anggaran' di kedua tabel.
     * Anda perlu menyesuaikan JOIN dan SELECT sesuai struktur tabel Anda.
     */
        
    public function getMonthlyRpd($tahun)
    {
        // Query untuk mengambil total RPD per bulan
        $builder = $this->db->table($this->table); // Menggunakan tabel RPD bulanan
        $builder->select('bulan, SUM(rencana_penarikan_dana) as total_rpd');
        $builder->where('tahun', $tahun);
        $builder->groupBy('bulan');
        $builder->orderBy('bulan', 'ASC'); // Urutkan berdasarkan bulan

        $results = $builder->get()->getResultArray();

        // Format data untuk grafik (sesuai kebutuhan library grafik Anda)
        $formattedData = [
            'labels' => [],
            'rpd_data' => []
        ];

        // Inisialisasi array dengan 0 untuk semua bulan (1-12)
        $monthlyTotals = array_fill(1, 12, ['total_rpd' => 0]);

        // Isi data yang ada dari hasil query
        foreach ($results as $row) {
            $monthlyTotals[(int)$row['bulan']] = [
                'total_rpd' => (float)$row['total_rpd']
            ];
        }

        // Format untuk output grafik
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 1; $i <= 12; $i++) {
            $formattedData['labels'][] = $namaBulan[$i-1];
            $formattedData['rpd_data'][] = $monthlyTotals[$i]['total_rpd'];
        }

        return $formattedData;
    }
}
