<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\KroModel;
use App\Models\RoModel;

class DropdownController extends BaseController
{
    public function getKegiatan($programId)
    {
        $kegiatanModel = new KegiatanModel();
        $kegiatan = $kegiatanModel->where('program_id', $programId)->findAll();

        $options = '<option value="">Pilih Kegiatan</option>';
        foreach ($kegiatan as $item) {
            $options .= "<option value='{$item['id']}'>{$item['nama_kegiatan']}</option>";
        }

        return $this->response->setJSON(['options' => $options]);
    }

    public function getKro($kegiatanId)
    {
        $kroModel = new KroModel();
        $kro = $kroModel->where('kegiatan_id', $kegiatanId)->findAll();

        $options = '<option value="">Pilih KRO</option>';
        foreach ($kro as $item) {
            $options .= "<option value='{$item['id']}'>{$item['nama_kro']}</option>";
        }

        return $this->response->setJSON(['options' => $options]);
    }

    public function getRo($kroId)
    {
        $roModel = new RoModel();
        $ro = $roModel->where('kro_id', $kroId)->findAll();

        $options = '<option value="">Pilih RO</option>';
        foreach ($ro as $item) {
            $options .= "<option value='{$item['id']}'>{$item['nama_ro']}</option>";
        }

        return $this->response->setJSON(['options' => $options]);
    }
}
