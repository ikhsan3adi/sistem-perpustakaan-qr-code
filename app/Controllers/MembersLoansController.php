<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;

class MembersLoansController extends ResourceController
{

    protected LoanModel $loanModel;
    protected FineModel $fineModel;
    protected MemberModel $memberModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;
        $this->memberModel = new MemberModel;
        $this->bookModel = new BookModel;

        helper('upload');
    }

    public function loans()
    {
        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $members = $this->memberModel
                ->like('first_name', $param, insensitiveSearch: true)
                ->orLike('last_name', $param, insensitiveSearch: true)
                ->orLike('email', $param, insensitiveSearch: true)
                ->orWhere('uid', $param)
                ->findAll();

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });

            if (empty($members)) {
                return view('loans/member', ['msg' => 'Member not found']);
            }

            return view('loans/member', ['members' => $members]);
        }

        return view('home/loans');
    }
}
