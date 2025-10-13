<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\ProgramModel;
use CodeIgniter\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        $model = new KegiatanModel();
        $data['kegiatan'] = $model->select('kegiatan.*, program.nama_program')
                                  ->join('program', 'program.id = kegiatan.program_id')
                                  ->findAll();
        return view('masterdata/kegiatan/index', $data);
    }

    public function create()
    {
        $programModel = new ProgramModel();
        $data['programs'] = $programModel->findAll();
        return view('masterdata/kegiatan/create', $data);
    }

    public function store()
    {
        $model = new KegiatanModel();
        $model->save($this->request->getPost());
        return redirect()->to('/kegiatan');
    }

    public function edit($id)
    {
        $model = new KegiatanModel();
        $programModel = new ProgramModel();
        $data['kegiatan'] = $model->find($id);
        $data['programs'] = $programModel->findAll();
        return view('masterdata/kegiatan/edit', $data);
    }

    public function update($id)
    {
        $model = new KegiatanModel();
        $model->update($id, $this->request->getPost());
        return redirect()->to('/kegiatan');
    }

    public function delete($id)
    {
        $model = new KegiatanModel();
        $model->delete($id);
        return redirect()->to('/kegiatan');
    }
}
