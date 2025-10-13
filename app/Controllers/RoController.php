<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoModel;
use App\Models\KroModel;

class RoController extends BaseController
{
    protected $roModel;

    public function __construct()
    {
        $this->roModel = new RoModel();
    }

    public function index()
    {
        $data['ros'] = $this->roModel
            ->select('ro.*, kro.kode_kro, kro.nama_kro')
            ->join('kro', 'kro.id = ro.kro_id')
            ->findAll();

        return view('masterdata/ro/ro_index', $data);
    }

    public function roList($kegiatan_id)
    {
        $data = $this->roModel
            ->select('ro.*, kro.kegiatan_id, kegiatan.program_id')
            ->join('kro', 'kro.id = ro.kro_id')
            ->join('kegiatan', 'kegiatan.id = kro.kegiatan_id')
            ->where('kegiatan.id', $kegiatan_id)
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function create()
    {
        $kroModel = new KroModel();
        $data['kros'] = $kroModel->findAll();

        return view('masterdata/ro/ro_create', $data);
    }

    public function store()
    {
        $this->roModel->insert([
            'kro_id' => $this->request->getPost('kro_id'),
            'kode_ro' => $this->request->getPost('kode_ro'),
            'nama_ro' => $this->request->getPost('nama_ro')
        ]);

        return redirect()->to(base_url('ro'))->with('success', 'Data RO berhasil disimpan.');
    }

    public function edit($id)
    {
        $data['ro'] = $this->roModel->find($id);
        $kroModel = new KroModel();
        $data['kros'] = $kroModel->findAll();

        return view('masterdata/ro/ro_edit', $data);
    }

    public function update($id)
    {
        $this->roModel->update($id, [
            'kro_id' => $this->request->getPost('kro_id'),
            'kode_ro' => $this->request->getPost('kode_ro'),
            'nama_ro' => $this->request->getPost('nama_ro')
        ]);

        return redirect()->to(base_url('ro'))->with('success', 'Data RO berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->roModel->delete($id);
        return redirect()->to(base_url('ro'))->with('success', 'Data RO berhasil dihapus.');
    }
}
