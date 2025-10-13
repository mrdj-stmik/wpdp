<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\KroModel;
use App\Models\RoModel;
use App\Models\KomponenModel;

class KegiatanController extends BaseController
{
    public function create()
    {
        $programModel = new \App\Models\ProgramModel();
        $data['programs'] = $programModel->findAll();
        return view('kegiatan/form', $data);
    }

public function simpan()
    {
        $model = new \App\Models\KegiatanModel();
        $model->insert([
            'program_id'     => $this->request->getPost('program_id'),
            'kode_kegiatan'  => $this->request->getPost('kode_kegiatan'),
            'nama_kegiatan'  => $this->request->getPost('nama_kegiatan'),
        ]);
        return redirect()->to('/kegiatan')->with('success', 'Data kegiatan berhasil disimpan.');
    }


    public function form()
    {
        $programModel = new ProgramModel();
        $data['programs'] = $programModel->findAll();

        return view('wpdp_form', $data);
    }

    public function getKegiatan($programId)
    {
        $model = new KegiatanModel();
        $data = $model->where('program_id', $programId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getKro($kegiatanId)
    {
        $model = new KroModel();
        $data = $model->where('kegiatan_id', $kegiatanId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getRo($kroId)
    {
        $model = new RoModel();
        $data = $model->where('kro_id', $kroId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getKomponen($roId)
    {
        $model = new KomponenModel();
        $data = $model->where('ro_id', $roId)->findAll();
        return $this->response->setJSON($data);
    }
}