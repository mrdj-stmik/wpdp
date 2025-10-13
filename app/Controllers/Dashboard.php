<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RpdModel;
use App\Models\WpdpModel;
use App\Models\AkunModel;

class Dashboard extends BaseController
{
    protected $rpdModel;
    protected $wpdpModel;
    protected $akunModel;
    protected $db;

    public function __construct()
    {
        $this->rpdModel = new RpdModel();
        $this->wpdpModel = new WpdpModel();
        $this->akunModel = new AkunModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tahunAnggaranAktif = date('Y');
        $nomorBulanSekarang = date('n');

        $bulanMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $namaBulanIndonesia = $bulanMap[$nomorBulanSekarang] ?? date('F');

        $paguAnggaranAwal = $this->rpdModel->getTotalPaguAwal($tahunAnggaranAktif);
        $paguRevisiTerakhir = $this->rpdModel->getTotalPaguRevisiTerakhir($tahunAnggaranAktif);
        $totalRpdSetahun = $this->wpdpModel->getTotalRpdSetahun($tahunAnggaranAktif);
        $totalRealisasiWpdpSetahun = $this->wpdpModel->getTotalRealisasiSetahun($tahunAnggaranAktif);
        $sisaPagu = $paguRevisiTerakhir - $totalRealisasiWpdpSetahun;

        $summaryData = [
            'pagu_anggaran_awal' => $paguAnggaranAwal,
            'pagu_revisi_terakhir' => $paguRevisiTerakhir,
            'total_rpd_setahun' => $totalRpdSetahun,
            'total_realisasi_wpdp_setahun' => $totalRealisasiWpdpSetahun,
            'sisa_pagu' => $sisaPagu,
        ];

        $monthlyRpdData = $this->wpdpModel->getMonthlyRpd($tahunAnggaranAktif);
        $monthlyRealisasiData = $this->wpdpModel->getMonthlyRealisasi($tahunAnggaranAktif);

        $monthlyDataForChart = [
            'labels' => $monthlyRpdData['labels'],
            'rpd_data' => $monthlyRpdData['rpd_data'],
            'realisasi_data' => $monthlyRealisasiData['realisasi_data']
        ];

        $filterBulan = array_slice($bulanMap, 0, $nomorBulanSekarang);

        $highlights = $this->db->table('wpdp_bulanan wb')
            ->select('ro.kode_ro, ro.nama_ro, wb.bulan, wb.tahun')
            ->join('detil det', 'det.id = wb.detil_id')
            ->join('akun akun', 'akun.id = det.akun_id') // Gunakan akun_id untuk join
            ->join('komponen kom', 'kom.id = akun.komponen_id') // Gunakan komponen_id dari tabel akun
            ->join('ro ro', 'ro.id = kom.ro_id') // Gunakan ro_id dari tabel komponen
            ->where('wb.tahun', $tahunAnggaranAktif)
            ->whereIn('wb.bulan', $filterBulan) // Filter bulan dari Januari hingga bulan sekarang
            ->where('wb.realisasi_dp', 0)
            ->orderBy('wb.bulan', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Dashboard RPD & WPDP',
            'summary' => $summaryData,
            'monthly_data' => $monthlyDataForChart,
            'highlights' => $highlights,
            'tahun_anggaran_aktif' => $tahunAnggaranAktif,
            'nama_bulan_sekarang' => $namaBulanIndonesia,
        ];

        return view('dashboard/index', $data);
    }
}