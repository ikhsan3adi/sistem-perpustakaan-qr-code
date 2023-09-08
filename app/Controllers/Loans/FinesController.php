<?php

namespace App\Controllers\Loans;

use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class FinesController extends ResourceController
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

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $itemPerPage = 20;

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $fines = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->orLike('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'fines');
        } else {
            $fines = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->paginate($itemPerPage, 'fines');
        }

        $paidOffFilter = ($this->request->getVar('paid-off') ?? 'false') === 'true';

        if ($paidOffFilter) {
            $fines = array_filter($fines, function ($fine) {
                return $fine['paid_at'] != null || ($fine['amount_paid'] ?? 0) >= $fine['fine_amount'];
            });
        } else {
            $fines = array_filter($fines, function ($fine) {
                return $fine['paid_at'] == null || $fine['fine_amount'] > ($fine['amount_paid'] ?? 0);
            });
        }

        $fines = array_filter($fines, function ($fine) {
            return $fine['deleted_at'] == null && $fine['return_date'] != null && $fine['fine_deleted'] == null;
        });

        $data = [
            'paidOffFilter' => $paidOffFilter,
            'fines'         => $fines,
            'pager'         => $this->loanModel->pager,
            'currentPage'   => $this->request->getVar('page_fines') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search')
        ];

        return view('fines/index', $data);
    }

    public function searchReturn()
    {
        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $returns = $this->loanModel
                ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->like('first_name', $param, insensitiveSearch: true)
                ->orLike('last_name', $param, insensitiveSearch: true)
                ->orLike('email', $param, insensitiveSearch: true)
                ->orLike('title', $param, insensitiveSearch: true)
                ->orLike('author', $param, insensitiveSearch: true)
                ->orLike('publisher', $param, insensitiveSearch: true)
                ->orWhere('loans.uid', $param)
                ->orWhere('members.uid', $param)
                ->findAll();

            $returns = array_filter($returns, function ($return) {
                return $return['deleted_at'] == null && $return['return_date'] != null && $return['fine_deleted'] == null;
            });

            if (empty($returns)) {
                return view('fines/return', ['msg' => 'Loan not found']);
            }

            return view('fines/return', ['returns' => $returns]);
        }

        return view('fines/search_return');
    }

    public function pay($uid = null, $validation = null, $oldInput = null)
    {
        $returns = $this->loanModel
            ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, racks.name as rack, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->where('loans.uid', $uid)
            ->findAll();

        $return = array_filter($returns, function ($r) {
            return $r['deleted_at'] == null && $r['fine_id'] != null && $r['return_date'] != null && $r['fine_deleted'] == null && $r['paid_at'] == null;
        });

        if (empty($return)) {
            throw new PageNotFoundException('Return not found');
        }

        return view('fines/pay', [
            'validation' => $validation ?? \Config\Services::validation(),
            'oldInput'   => $oldInput,
            'return'     => $return[array_key_first($return)]
        ]);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    // public function new()
    // {
    //! Not implemented
    // }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    // public function create()
    // {
    //! Not implemented
    // }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    // public function edit($id = null)
    // {
    //! Not implemented
    // }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($uid = null)
    {
        if (!$this->validate([
            'nominal'  => 'required|numeric|greater_than_equal_to[1000]'
        ])) {
            return $this->pay($uid, \Config\Services::validation(), $this->request->getVar());
        }

        $returns = $this->loanModel
            ->select('fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->where('loans.uid', $uid)
            ->findAll();

        $return = array_filter($returns, function ($r) {
            return $r['deleted_at'] == null && $r['fine_id'] != null && $r['return_date'] != null && $r['fine_deleted'] == null && $r['paid_at'] == null;
        });

        if (empty($return)) {
            throw new PageNotFoundException('Return not found');
        }

        $return = $return[array_key_first($return)];

        $nominal = $this->request->getVar('nominal');
        $newAmountPaid = intval($return['amount_paid'] ?? 0) + intval($nominal);

        if (!$this->fineModel->update(
            $return['fine_id'],
            [
                'amount_paid' => $newAmountPaid,
                'paid_at'     => $newAmountPaid >= $return['fine_amount'] ? Time::now()->toDateTimeString() : null
            ]
        )) {
            session()->setFlashdata(['msg' => 'Update failed']);
            return $this->pay($uid, \Config\Services::validation(), $this->request->getVar());
        }

        if ($newAmountPaid >= $return['fine_amount']) {
            deleteLoansQRCode($return['qr_code']);
            $this->loanModel->update($return['id'], ['qr_code' => null]);
        }

        session()->setFlashdata(['msg' => 'Update fine successful']);
        return redirect()->to('admin/fines');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    // public function delete($id = null)
    // {
    //! Not implemented
    // }
}
