<?php

namespace App\Controllers;

use App\Models\WpdpModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    protected $wpdpModel;

    public function __construct()
    {
        $this->wpdpModel = new WpdpModel();
    }

    public function exportComprehensiveReport($tahun = 2025)
    {
        $dataLaporan = $this->wpdpModel->getComprehensiveReportData($tahun);

        if (empty($dataLaporan)) {
            return redirect()->back()->with('error', 'Tidak ada data laporan untuk tahun ' . $tahun);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header Kolom
        $headerKolom = [
            'Nama Sasaran',
            'Nama Indikator',
            'Nama PMO',
            'Nama KRO',
            'Kode RO',
            'Nama RO',
            'Nama Ketua Tim',
            'Total Pagu Awal Detil RO',
            'Rekap Memo',
            'Rekap Kendala',
            'Rekap Solusi',
            'Rekap Rencana Tindak Lanjut',
            'Rekap Batas Tindak Lanjut',
        ];
        $sheet->fromArray($headerKolom, null, 'A1');

        // Isi Data Laporan
        $row = 2;
        foreach ($dataLaporan as $item) {
            $sheet->fromArray(
                [
                    $item['indikator'],
                    $item['sasaran'],
                    $item['nama_pmo'],
                    $item['nama_kro'],
                    $item['kode_ro'],
                    $item['nama_ro'],
                    $item['nama_ketua_tim'],
                    $item['total_pagu_awal_detil_ro'],
                    $item['rekap_memo'],
                    $item['rekap_kendala'],
                    $item['rekap_solusi'],
                    $item['rekap_rencana_tindak_lanjut'],
                    $item['rekap_batas_tindak_lanjut'],
                ],
                null,
                'A' . $row
            );
            $row++;
        }

        // Styling Header
        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:O1')->applyFromArray($styleHeader);

        // Auto Size Kolom
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Membuat File Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Komprehensif Tahun ' . $tahun . '.xlsx';

        // Mengirim Response ke Browser untuk Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function rekapitulasiRoKomponen()
    {
        $db = \Config\Database::connect();
        $query = $db->table('vw_rekap_pagu_per_ro_komponen')->get();
        $data['rekapitulasi'] = $query->getResultArray();
    
        return view('detil/index', $data);
    }
}