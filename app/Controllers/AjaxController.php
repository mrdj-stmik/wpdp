<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\KroModel;
use App\Models\RoModel;
use App\Models\KomponenModel;
use App\Models\AkunModel;
use App\Models\DetilModel;
use App\Models\ProgramModel;
use App\Models\RpdModel;

class AjaxController extends \App\Controllers\BaseController
{
    public function program($id = null)
    {
        $model = new ProgramModel();
        if ($id) {
            $data = $model->find($id);
        } else {
            $data = $model->findAll();
        }
        return $this->response->setJSON($data);
    }

    public function kegiatan($programId)
    {
        if (!$programId) {
            return $this->response->setJSON(['error' => 'Program ID tidak ada']);
        }
        $model = new KegiatanModel();
        $data = $model->where('program_id', $programId)->findAll();
        return $this->response->setJSON($data);
    }

    public function kro($kegiatanId)
    {
        $model = new KroModel();
        $data = $model->where('kegiatan_id', $kegiatanId)->findAll();
        return $this->response->setJSON($data);
    }

    public function ro($kroId)
    {
        $model = new RoModel();
        $data = $model->where('kro_id', $kroId)->findAll();
        return $this->response->setJSON($data);
    }

    public function komponen($roId)
    {
        $model = new KomponenModel();
        $data = $model->where('ro_id', $roId)->findAll();
        return $this->response->setJSON($data);
    }

    public function akun($komponenId)
    {
        $model = new AkunModel();
        $data = $model->where('komponen_id', $komponenId)->findAll();
        return $this->response->setJSON($data);
    }

    public function detil($akunId)
    {
        $model = new DetilModel();
        $data = $model->where('akun_id', $akunId)->findAll();
        return $this->response->setJSON($data);
    }

    public function rpd($detilId)
    {
        $model = new RpdModel();
        $data = $model->where('detil_id', $detilId)->findAll();
        return $this->response->setJSON($data);
    }
}
