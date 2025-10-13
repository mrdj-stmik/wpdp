<?php

namespace App\Controllers;

use App\Models\DetilModel;

class DetilController extends BaseController
{
    protected $detilModel;

    public function __construct()
    {
        $this->detilModel = new DetilModel();
    }

    public function index()
    {
        // Ambil data detil dengan nama RO dan Akun
        $data['detils'] = $this->detilModel->getDetilWithROAndAkun();

        // Kirim data ke view
        return view('masterdata/detil/detil_index', $data);
    }

    public function edit($id)
{
    // Ambil data detil berdasarkan ID
    $detil = $this->detilModel->find($id);

    if (!$detil) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Data detil tidak ditemukan.');
    }

    // Load model akun untuk dropdown
    $akunModel = new \App\Models\AkunModel();
    $data['akuns'] = $akunModel->select('akun.*, komponen.nama_komponen, ro.nama_ro')
                              ->join('komponen', 'komponen.id = akun.komponen_id', 'left')
                              ->join('ro', 'ro.id = komponen.ro_id', 'left')
                              ->findAll();

    // Kirim data ke view
    $data['detil'] = $detil;
    return view('masterdata/detil/detil_edit', $data);
}

public function update($id)
{
    // Validasi input
    $this->validate([
        'kode_detil' => 'required|max_length[25]',
        'nama_detil' => 'required|max_length[255]',
        'pagu_anggaran_awal' => 'required|numeric|greater_than_equal_to[0]',
        'akun_id' => 'required|integer',
    ]);

    // Ambil data dari form
    $data = [
        'kode_detil' => $this->request->getPost('kode_detil'),
        'nama_detil' => $this->request->getPost('nama_detil'),
        'pagu_anggaran_awal' => $this->request->getPost('pagu_anggaran_awal'),
        'akun_id' => $this->request->getPost('akun_id'),
        'pagu_revisi_terakhir' => $this->request->getPost('pagu_anggaran_awal'), // Update juga pagu revisi
    ];

    // Update data di database
    $this->detilModel->update($id, $data);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->to(base_url('detil'))->with('success', 'Data detil berhasil diperbarui.');
  }
public function delete($id)
{
    // Cek apakah data detil ada
    $detil = $this->detilModel->find($id);

    if (!$detil) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Data detil tidak ditemukan.');
    }

    // Hapus data dari database
    $this->detilModel->delete($id);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->to(base_url('detil'))->with('success', 'Data detil berhasil dihapus.');
}

    public function create()
    {
        // Load model akun untuk dropdown
        $akunModel = new \App\Models\AkunModel();
        $data['akuns'] = $akunModel->select('akun.*, komponen.nama_komponen, ro.nama_ro')
                                  ->join('komponen', 'komponen.id = akun.komponen_id', 'left')
                                  ->join('ro', 'ro.id = komponen.ro_id', 'left')
                                  ->findAll();
        
        // Tampilkan form tambah detil
        return view('masterdata/detil/detil_create', $data);
    }public function store()
{
    // Validasi input
    $validation = $this->validate([
        'kode_detil_input' => 'required|exact_length[6]|numeric',
        'nama_detil' => 'required|max_length[255]',
        'pagu_anggaran_awal' => 'required|numeric|greater_than_equal_to[0]',
        'nilai_pagu_terakhir' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'akun_id' => 'required|integer',
    ]);

    if (!$validation) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Generate kode detil lengkap berdasarkan akun
    $kodeDetilInput = $this->request->getPost('kode_detil_input');
    $akunId = $this->request->getPost('akun_id');
    $kodeDetilLengkap = $this->generateKodeDetil($akunId, $kodeDetilInput);

    // Ambil nilai pagu terakhir atau default ke pagu anggaran awal
    $paguAnggaranAwal = $this->request->getPost('pagu_anggaran_awal');
    $nilaiPaguTerakhir = $this->request->getPost('nilai_pagu_terakhir');
    if (empty($nilaiPaguTerakhir)) {
        $nilaiPaguTerakhir = $paguAnggaranAwal;
    }

    // Ambil data dari form
    $data = [
        'kode_detil' => $kodeDetilLengkap,
        'nama_detil' => $this->request->getPost('nama_detil'),
        'pagu_anggaran_awal' => $paguAnggaranAwal,
        'pagu_revisi_terakhir' => $nilaiPaguTerakhir,
        'akun_id' => $akunId,
    ];

    // Simpan data ke database
    $this->detilModel->insert($data);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->to(base_url('detil'))->with('success', 'Data detil berhasil ditambahkan.');
}

private function generateKodeDetil($akunId, $kodeDetilInput)
{
    // Load model akun dan join dengan hierarki lengkap
    $akunModel = new \App\Models\AkunModel();
    $akun = $akunModel->select('akun.kode_akun')
                     ->where('akun.id', $akunId)
                     ->first();
    
    if (!$akun) {
        throw new \Exception('Akun tidak ditemukan');
    }
    
    // Ambil kode akun dan ganti 6 digit terakhir dengan input user
    $kodeAkun = $akun['kode_akun'];
    
    // Format biasanya: prefix_6digit, kita ganti 6 digit terakhir
    $prefix = substr($kodeAkun, 0, -6); // Ambil semua kecuali 6 digit terakhir
    $kodeDetilLengkap = $prefix . $kodeDetilInput;
    
    return $kodeDetilLengkap;
}
}