<?php

namespace App\Controllers\Loans;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class ReturnsController extends ResourceController
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
            $loans = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->orLike('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'returns');
        } else {
            $loans = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
                ->paginate($itemPerPage, 'returns');
        }

        $loans = array_filter($loans, function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] != null && $loan['fine_deleted'] == null;
        });

        $data = [
            'loans'         => $loans,
            'pager'         => $this->loanModel->pager,
            'currentPage'   => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'   => $itemPerPage,
        ];

        return view('returns/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($uid = null)
    {
        $loan = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->where('loans.uid', $uid)
            ->where("return_date IS NOT NULL")
            ->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        if ($this->request->getGet('update-qr-code')) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = substr($loan['first_name'] . ($loan['last_name'] ? " {$loan['last_name']}" : ''), 0, 12) . '_' . substr($loan['title'], 0, 12);
            $qrCode = $qrGenerator->generateQRCode(
                $loan['uid'],
                labelText: $qrCodeLabel,
                dir: LOANS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            // delete former qr code
            deleteLoansQRCode($loan['qr_code']);

            $this->loanModel->update($loan['id'], ['qr_code' => $qrCode]);

            $loan = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->where('loans.uid', $uid)
                ->first();

            return redirect()->to("admin/returns/{$loan['uid']}");
        }

        $data = [
            'loan'         => $loan,
        ];

        return view('returns/show', $data);
    }

    public function searchLoan()
    {
        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $loans = $this->loanModel
                ->select('members.*, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->like('first_name', $param, insensitiveSearch: true)
                ->orLike('last_name', $param, insensitiveSearch: true)
                ->orLike('email', $param, insensitiveSearch: true)
                ->orLike('title', $param, insensitiveSearch: true)
                ->orLike('author', $param, insensitiveSearch: true)
                ->orLike('publisher', $param, insensitiveSearch: true)
                ->orWhere('loans.uid', $param)
                ->orWhere('members.uid', $param)
                ->findAll();

            $loans = array_filter($loans, function ($loan) {
                return $loan['deleted_at'] == null && $loan['return_date'] == null;
            });

            if (empty($loans)) {
                return view('returns/loan', ['msg' => 'Loan not found']);
            }

            return view('returns/loan', ['loans' => $loans]);
        }

        return view('returns/search_loan');
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $loanUid = $this->request->getVar('loan-uid');

        if (empty($loanUid)) {
            session()->setFlashdata(['msg' => 'Select loan first', 'error' => true]);
            return redirect()->to('admin/returns/new/search');
        }

        $loans = $this->loanModel
            ->select('members.*, books.*, categories.name as category, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->where('loans.uid', $loanUid)
            ->findAll();

        $loan = array_filter($loans, function ($l) {
            return $l['deleted_at'] == null && $l['return_date'] == null;
        });

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $data = [
            'loan'       => $loan[array_key_first($loan)],
            'validation' => $validation ?? \Config\Services::validation()
        ];

        return view('returns/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $date = Time::parse($this->request->getVar('date') ?? 'now', locale: 'id');
        $loanUid = $this->request->getVar('loan_uid');

        $loan = $this->loanModel->where('uid', $loanUid)->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

        $isLate = $date->isAfter($loanDueDate);

        if ($isLate) {
            if (!$this->loanModel->update($loan['id'], [
                'return_date' => $date->toDateTimeString()
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('admin/returns/new?loan-uid=' . $loan['uid']);
            }

            $finePerDay = intval(getenv('amountFinesPerDay'));
            $daysLate = $date->today()->difference($loanDueDate)->getDays();
            $totalFine = abs($daysLate) * $loan['quantity'] * $finePerDay;

            if (!$this->fineModel->save([
                'loan_id' => $loan['id'],
                'fine_amount' => $totalFine,
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('admin/returns/new?loan-uid=' . $loan['uid']);
            }
        } else {
            deleteLoansQRCode($loan['qr_code']);
            if (!$this->loanModel->update($loan['id'], [
                'return_date' => $date->toDateTimeString(),
                'qr_code' => null
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('admin/returns/new?loan-uid=' . $loan['uid']);
            }
        }

        session()->setFlashdata(['msg' => 'Success', 'error' => false]);
        return redirect()->to('admin/returns');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    // public function edit($uid = null)
    // {
    //! Not implemented
    // }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    // public function update($uid = null)
    // {
    //! Not implemented
    // }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($uid = null)
    {
        $loans = $this->loanModel
            ->select('members.*, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.uid', $uid)->findAll();

        $loans = array_filter($loans, function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] != null;
        });

        $loan = $loans[0];

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $qrGenerator = new QRGenerator();

        $qrCodeLabel = substr($loan['first_name'] . ($loan['last_name'] ? " {$loan['last_name']}" : ''), 0, 12) . '_' . substr($loan['title'], 0, 12);

        $qrCode = $qrGenerator->generateQRCode(
            data: $loan['uid'],
            labelText: $qrCodeLabel,
            dir: LOANS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->loanModel->update($loan['id'], [
            'return_date' => null,
            'qr_code' => $qrCode
        ])) {
            deleteLoansQRCode($qrCode);

            session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
            return redirect()->to('admin/returns/' . $loan['uid']);
        }

        $isLate = Time::parse($loan['return_date'])->isAfter(Time::parse($loan['due_date']));

        if ($isLate) {
            $fine = $this->fineModel->where('loan_id', $loan['id'])->first();
            if (!empty($fine)) $this->fineModel->delete($fine['id']);
        }

        session()->setFlashdata(['msg' => 'Success', 'error' => false]);
        return redirect()->to('admin/returns');
    }
}
