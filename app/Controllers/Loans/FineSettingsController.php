<?php

namespace App\Controllers\Loans;

use App\Models\FinesPerDayModel;
use CodeIgniter\RESTful\ResourceController;

class FineSettingsController extends ResourceController
{
    public function index()
    {
        return view('fines/settings', [
            'fine' => FinesPerDayModel::get(),
            'validation' => \Config\Services::validation()
        ]);
    }

    public function show($id = null)
    {
        return $this->index();
    }

    public function update($id = null)
    {
        if (!$this->validate([
            'amount' => 'required|integer|greater_than_equal_to[1000]'
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'fine' => FinesPerDayModel::get(),
            ];

            return view('fines/settings', $data);
        }
        try {
            FinesPerDayModel::updateAmount($this->request->getVar('amount'));

            session()->setFlashdata(['msg' => 'Update fine amount successful']);
            return redirect('admin/fines/settings');
        } catch (\Throwable $e) {
            session()->setFlashdata(['msg' => $e->getMessage(), 'error' => true]);
            return redirect('admin/fines/settings');
        }
    }
}
