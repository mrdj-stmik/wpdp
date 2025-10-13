<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KroModel;
use App\Models\KegiatanModel;

class KroController extends BaseController
{
    protected $kroModel;

    public function __construct()
    {
        $this->kroModel = new KroModel();
    }

    public function index()
    {
        $data['kros'] = $this->kroModel
            ->select('kro.*, kegiatan.kode_kegiatan, kegiatan.nama_kegiatan')
            ->join('kegiatan', 'kegiatan.id = kro.kegiatan_id')
            ->findAll();

        return view('masterdata/kro/kro_index', $data);
    }

    public function create()
    {
        $kegiatanModel = new KegiatanModel();
        $data['kegiatans'] = $kegiatanModel->findAll();

        return view('masterdata/kro/kro_create', $data);
    }

    public function store()
    {
        $this->kroModel->insert([
            'kegiatan_id' => $this->request->getPost('kegiatan_id'),
            'kode_kro'    => $this->request->getPost('kode_kro'),
            'nama_kro'    => $this->request->getPost('nama_kro')
        ]);

        return redirect()->to(base_url('kro'))->with('success', 'Data KRO berhasil disimpan.');
    }

    public function edit($id)
    {
        $data['kro'] = $this->kroModel->find($id);
        $kegiatanModel = new KegiatanModel();
        $data['kegiatans'] = $kegiatanModel->findAll();

        return view('masterdata/kro/kro_edit', $data);
    }

    public function update($id)
    {
        $this->kroModel->update($id, [
            'kegiatan_id' => $this->request->getPost('kegiatan_id'),
            'kode_kro'    => $this->request->getPost('kode_kro'),
            'nama_kro'    => $this->request->getPost('nama_kro')
        ]);

        return redirect()->to(base_url('kro'))->with('success', 'Data KRO berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->kroModel->delete($id);
        return redirect()->to(base_url('kro'))->with('success', 'Data KRO berhasil dihapus.');
    }
}
