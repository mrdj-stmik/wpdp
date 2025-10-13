<?php
namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\KroModel;
use App\Models\RoModel;

class ProgramController extends BaseController
{
public function index() {
    $model = new ProgramModel();
    return view('masterdata/program/index', ['programs' => $model->findAll()]);
}

public function create() {
    return view('masterdata/program/create');
}

public function store() {
    $model = new ProgramModel();
    $model->save($this->request->getPost());
    return redirect()->to('/program');
}

public function edit($id) {
    $model = new ProgramModel();
    return view('masterdata/program/edit', ['program' => $model->find($id)]);
}

public function update($id) {
    $model = new ProgramModel();
    $model->update($id, $this->request->getPost());
    return redirect()->to('/program');
}

public function delete($id) {
    $model = new ProgramModel();
    $model->delete($id);
    return redirect()->to('/program');
}
}