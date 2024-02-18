<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use CodeIgniter\I18n\Time;
use App\Models\MemberModel;
use CodeIgniter\Controller;
use App\Libraries\QRGenerator;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Exceptions\PageNotFoundException;

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

    public function show($uid = null)
    {
        $loan = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->where('loans.uid', $uid)
            ->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        if ($this->request->getGet('update-qr-code') && $loan['return_date'] == null) {
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

            return redirect()->to("/loans/{$loan['uid']}");
        }

        $data = [
            'loan'         => $loan,
        ];

        return view('home/members/loans_show', $data);
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
                return view('home/members/member', ['msg' => 'Member not found']);
            }

            return view('home/members/member', ['members' => $members]);
        }

        return view('home/members/loans');
    }

    public function searchBook()
    {
        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');
            $memberUid = $this->request->getVar('memberUid');

            if (empty($param)) return;

            if (empty($memberUid)) {
                return view('/loans', ['msg' => 'Member UID is empty']);
            }

            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->like('title', $param, insensitiveSearch: true)
                ->orLike('slug', $param, insensitiveSearch: true)
                ->orLike('author', $param, insensitiveSearch: true)
                ->orLike('publisher', $param, insensitiveSearch: true)
                ->orWhere('isbn', $param)
                ->findAll();

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });

            if (empty($books)) {
                return view('home/members/book', ['msg' => 'Book not found']);
            }

            $books = array_map(function ($book) {
                $newBook = $book;
                $newBook['stock'] = $this->getRemainingBookStocks($book);
                return $newBook;
            }, $books);

            return view('home/members/book', ['books' => $books, 'memberUid' => $memberUid]);
        }

        $memberUid = $this->request->getVar('member-uid');

        if (empty($memberUid)) {
            session()->setFlashdata(['msg' => 'Select member first', 'error' => true]);
            return redirect()->to('/loans');
        }

        $member = $this->memberModel->where('uid', $memberUid)->first();

        if (empty($member)) {
            session()->setFlashdata(['msg' => 'Member not found', 'error' => true]);
            return redirect()->to('/loans');
        }

        return view('home/members/search_book', ['member' => $member]);
    }

    protected function getRemainingBookStocks($book)
    {
        $loans = $this->loanModel->where([
            'book_id' => $book['id'],
            'return_date' => null
        ])->findAll();

        $loanCount = array_reduce(
            array_map(function ($loan) {
                return $loan['quantity'];
            }, $loans),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        return $book['quantity'] - $loanCount;
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new($validation = null, $oldInput = null)
    {

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/loans/books/search');
        }

        $member = $this->memberModel
            ->where('uid', $this->request->getVar('member_uid'))
            ->first();

        $books = [];

        $bookSlugs = $this->request->getVar('slugs');

        if (empty($bookSlugs)) {
            return redirect()->back();
        }

        foreach ($bookSlugs as $slug) {
            $book = $this->bookModel
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->where('books.slug', $slug)->first();

            if (!empty($book)) {
                $book['stock'] = $this->getRemainingBookStocks($book);
                array_push($books, $book);
            }
        }

        $data = [
            'books'      => $books,
            'member'     => $member,
            'validation' => $validation ?? \Config\Services::validation(),
            'oldInput'   => $oldInput,
        ];

        return view('home/members/create', $data);
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $validation = [
            'member_uid' => 'required|string|max_length[64]',
        ];

        $bookSlugs = $this->request->getVar('slugs') or die();

        foreach ($bookSlugs as $slug) {
            $validation['quantity-' . $slug] = 'required|numeric|integer|greater_than[0]|less_than_equal_to[10]';
            $validation['duration-' . $slug] = 'required|numeric|integer|greater_than[0]|less_than_equal_to[30]';
        }

        if (!$this->validate($validation)) {
            return $this->new(\Config\Services::validation(), $this->request->getVar());
        }

        $memberUid = $this->request->getVar('member_uid') or die();

        $member = $this->memberModel->where('uid', $memberUid)->first();

        if (empty($member)) {
            session()->setFlashdata(['msg' => 'Member not found']);
            return redirect()->to('loans/member/search');
        }

        $newLoanIds = [];

        foreach ($bookSlugs as $slug) {
            $duration = $this->request->getVar('duration-' . $slug);
            $quantity = $this->request->getVar('quantity-' . $slug);

            $book = $this->bookModel->where('slug', $slug)->first();

            if (empty($duration) || empty($quantity) || empty($book)) {
                continue;
            }

            $loanUid = sha1($book['slug'] . $member['uid'] . time());

            $qrGenerator = new QRGenerator();

            $qrCodeLabel = substr($member['first_name'] . ($member['last_name'] ? " {$member['last_name']}" : ''), 0, 12) . '_' . substr($book['title'], 0, 12);

            $qrCode = $qrGenerator->generateQRCode(
                data: $loanUid,
                labelText: $qrCodeLabel,
                dir: LOANS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            $newLoan = [
                'book_id' => $book['id'],
                'quantity' => $quantity,
                'member_id' => $member['id'],
                'uid' => $loanUid,
                'loan_date' => Time::now()->toDateTimeString(),
                'due_date' => Time::now()->addDays(intval($duration))->toDateTimeString(),
                'qr_code' => $qrCode,
            ];

            $this->loanModel->insert($newLoan);

            array_push($newLoanIds, $this->loanModel->getInsertID());
        }

        $newLoans = array_map(function ($id) {
            return $this->loanModel->select('members.*, members.uid as member_uid, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->where('loans.id', $id)->first();
        }, $newLoanIds);

        return view('home/members/result', [
            'newLoans'  => $newLoans
        ]);
    }
    public function delete($uid = null)
    {
        $loan = $this->loanModel->where('uid', $uid)->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        };

        if (!$this->loanModel->delete($loan['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete loan', 'error' => true]);
            return redirect()->back();
        }

        deleteLoansQRCode($loan['qr_code']);

        session()->setFlashdata(['msg' => 'Loan deleted successfully']);
        return redirect()->to('loans/member/search');
    }
    
}
