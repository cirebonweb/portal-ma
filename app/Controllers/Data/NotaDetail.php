<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\FinishingModel;
use App\Models\KonsumenModel;
use App\Models\NotaIsiModel;

class NotaDetail extends BaseController
{
    private NotaIsiModel $notaIsiModel;
    private FinishingModel $finishingModel;
    private KonsumenModel $konsumenModel;

    public function __construct()
    {
        $this->notaIsiModel = new NotaIsiModel();
        $this->finishingModel = new FinishingModel();
        $this->konsumenModel = new KonsumenModel();
    }

    public function index(int $id)
    {
        return view('data/nota_isi', [
            'pageTitle'     => 'Detail Nota',
            'navTitle'      => 'Detail Nota',
            'navigasi'      => '<a href="/data">Data</a> &nbsp; / &nbsp; <a href="/nota">Nota</a> &nbsp;',
            'id'            => $id,
            'menuFinishing' => $this->finishingModel->getDropdown(),
            'statusDraft'   => (int) $this->notaIsiModel->where('nota_id', $id)->where('status', 0)->countAllResults(),
            'menuKonsumen'  => $this->konsumenModel->getDropdown(),
        ]);
    }
}
