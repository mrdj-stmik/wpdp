<?php

namespace App\Controllers;

use App\Models\DetilRevisiModel;

class DetilRevisi extends BaseController
{
    protected $detilRevisiModel;

    public function __construct()
    {
        $this->detilRevisiModel = new DetilRevisiModel();
    }

public function index($id_detil)
{
    // Validasi input id_detil
    if (!is_numeric($id_detil)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('ID Detil tidak valid.');
    }

    // Jumlah data per halaman
    $perPage = 10;

    // Mengambil data revisi dengan pagination
    $data['revisions'] = $this->detilRevisiModel->where('detil_id', $id_detil)
                                                ->orderBy('revisi_ke', 'ASC')
                                                ->paginate($perPage);
    $data['pager'] = $this->detilRevisiModel->pager; // Kirimkan objek pager ke view
    
    // Fallback jika pagination kosong, coba tanpa pagination untuk debugging
    if (empty($data['revisions'])) {
        $data['revisions'] = $this->detilRevisiModel->where('detil_id', $id_detil)
                                                    ->orderBy('revisi_ke', 'ASC')
                                                    ->findAll();
    }
    $data['id_detil'] = $id_detil;

    // Debug info (remove in production)
    log_message('debug', 'DetilRevisi index - id_detil: ' . $id_detil . ', revisions count: ' . count($data['revisions']));

    // Memuat view dan mengirimkan data
    return view('detil_revisi/index', $data);
}

    public function create($id_detil)
    {
        $data['id_detil'] = $id_detil;

        return view('detil_revisi/form', $data);
    }

    public function store()
    {
        // Validasi input
        $validation = $this->validate([
            'id_detil' => 'required|integer',
            'revisi_ke' => 'required|integer|greater_than[0]',
            'volume' => 'required|numeric|greater_than[0]',
            'harga_satuan' => 'required|numeric|greater_than[0]',
            'catatan' => 'max_length[50]',
            'sd_cp' => 'max_length[2]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali input Anda.');
        }

        $id_detil = $this->request->getPost('id_detil');
        $volume = $this->request->getPost('volume');
        $harga_satuan = $this->request->getPost('harga_satuan');
        $jumlah_biaya = $volume * $harga_satuan;

        $data = [
            'detil_id'      => $id_detil,
            'revisi_ke'     => $this->request->getPost('revisi_ke'),
            'volume'        => $volume,
            'harga_satuan'  => $harga_satuan,
            'jumlah_biaya'  => $jumlah_biaya,
            'catatan'       => $this->request->getPost('catatan'),
            'sd_cp'         => $this->request->getPost('sd_cp'),
            'tanggal_revisi' => date('Y-m-d H:i:s'),
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $this->detilRevisiModel->insert($data);

        // Cari revisi terbaru untuk detil ini
        $latestRevision = $this->detilRevisiModel->where('detil_id', $id_detil)
                                                 ->orderBy('tanggal_revisi', 'DESC')
                                                 ->orderBy('revisi_ke', 'DESC')
                                                 ->first();

        // Update pagu_revisi_terakhir di tabel detil
        $builder = $db->table('detil');
        $builder->where('id', $id_detil);
        $builder->update(['pagu_revisi_terakhir' => $latestRevision['jumlah_biaya'] ?? 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data revisi.');
        }

        return redirect()->to('detilrevisi/index/' . $id_detil)->with('success', 'Revisi berhasil disimpan.');
    }

    public function delete($id_detil_revisi)
    {
        $revisi_data = $this->detilRevisiModel->find($id_detil_revisi);

        if (!$revisi_data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data revisi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->detilRevisiModel->delete($id_detil_revisi);

        // Cari revisi terbaru untuk detil ini setelah penghapusan
        $lastRevision = $this->detilRevisiModel->where('detil_id', $revisi_data['detil_id'])
                                               ->orderBy('tanggal_revisi', 'DESC')
                                               ->orderBy('revisi_ke', 'DESC')
                                               ->first();

        $builder = $db->table('detil');
        $builder->where('id', $revisi_data['detil_id']);
        $builder->update(['pagu_revisi_terakhir' => $lastRevision['jumlah_biaya'] ?? 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menghapus data revisi.');
        }

        return redirect()->to('detilrevisi/index/' . $revisi_data['detil_id'])->with('success', 'Revisi berhasil dihapus.');
    }
    /**
     * Menampilkan form untuk mengedit revisi detil.
     *
     * @param int $id_detil_revisi ID revisi detil yang akan diedit.
     * @return \CodeIgniter\HTTP\Response
     */

    public function edit($id_detil_revisi)
    {
        $revisi_data = $this->detilRevisiModel->find($id_detil_revisi);

        if (!$revisi_data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data revisi tidak ditemukan.');
        }

        $data['revisi_data'] = $revisi_data;
        $data['id_detil'] = $revisi_data['detil_id'];

        return view('detil_revisi/form', $data);
    }

    public function update($id_detil_revisi)
    {
        $revisi_data = $this->detilRevisiModel->find($id_detil_revisi);

        if (!$revisi_data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data revisi tidak ditemukan.');
        }

        // Validasi input
        $validation = $this->validate([
            'volume' => 'required|numeric|greater_than[0]',
            'harga_satuan' => 'required|numeric|greater_than[0]',
            'catatan' => 'max_length[50]',
            'sd_cp' => 'max_length[2]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali input Anda.');
        }

        $volume = $this->request->getPost('volume');
        $harga_satuan = $this->request->getPost('harga_satuan');
        $jumlah_biaya = $volume * $harga_satuan;

        $data = [
            'volume'       => $volume,
            'harga_satuan' => $harga_satuan,
            'jumlah_biaya' => $jumlah_biaya,
            'catatan'      => $this->request->getPost('catatan'),
            'sd_cp'        => $this->request->getPost('sd_cp'),
            'tanggal_revisi' => date('Y-m-d H:i:s'),
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        // Update data revisi
        $this->detilRevisiModel->update($id_detil_revisi, $data);

        // Cari revisi terbaru untuk detil ini
        $latestRevision = $this->detilRevisiModel->where('detil_id', $revisi_data['detil_id'])
                                                 ->orderBy('tanggal_revisi', 'DESC')
                                                 ->orderBy('revisi_ke', 'DESC')
                                                 ->first();

        // Update pagu_revisi_terakhir di tabel detil
        $builder = $db->table('detil');
        $builder->where('id', $revisi_data['detil_id']);
        $builder->update(['pagu_revisi_terakhir' => $latestRevision['jumlah_biaya'] ?? 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memperbarui data revisi.');
        }

        return redirect()->to('detilrevisi/index/' . $revisi_data['detil_id'])->with('success', 'Revisi berhasil diperbarui dan pagu detil telah diupdate.');
    }
}