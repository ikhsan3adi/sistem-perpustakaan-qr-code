<?php

namespace App\Controllers\Members;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\BookStockModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class MembersController extends ResourceController
{
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected BookStockModel $bookStockModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel;
        $this->bookModel = new BookModel;
        $this->bookStockModel = new BookStockModel;
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;

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
            $members = $this->memberModel
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'members');

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });
        } else {
            $members = $this->memberModel->paginate($itemPerPage, 'members');
        }

        $data = [
            'members'           => $members,
            'pager'             => $this->memberModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search')
        ];

        return view('members/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        $loans = $this->loanModel->where([
            'member_id' => $member['id'],
            'return_date' => null
        ])->findAll();

        $fines = $this->loanModel
            ->select('loans.id, fines.amount_paid, fines.fine_amount, fines.paid_at')
            ->join('fines', 'loans.id=fines.loan_id', 'LEFT')
            ->where('member_id', $member['id'])->findAll();

        $totakBooksLent = empty($loans) ? 0 : array_reduce(
            array_map(function ($loan) {
                return $loan['quantity'];
            }, $loans),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $return = array_filter($loans, function ($loan) {
            return $loan['return_date'] != null;
        });

        $lateLoans = array_filter($loans, function ($loan) {
            return $loan['return_date'] == null && Time::now()->isAfter(Time::parse($loan['due_date']));
        });

        $totalFines = array_reduce(
            array_map(function ($fine) {
                return $fine['fine_amount'];
            }, $fines),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $paidFines = array_reduce(
            array_map(function ($fine) {
                return $fine['amount_paid'];
            }, $fines),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $unpaidFines = $totalFines - $paidFines;

        // Create qr code if not exist
        if (!file_exists(MEMBERS_QR_CODE_PATH . $member['qr_code']) || empty($member['qr_code'])) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $member['first_name'] . ($member['last_name'] ? ' ' . $member['last_name'] : '');
            $qrCode = $qrGenerator->generateQRCode(
                $member['uid'],
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            $this->memberModel->update($member['id'], ['qr_code' => $qrCode]);
            $member = $this->memberModel->where('uid', $uid)->first();
        }

        $data = [
            'member'            => $member,
            'totalBooksLent'    => $totakBooksLent,
            'loanCount'         => count($loans),
            'returnCount'       => count($return),
            'lateCount'         => count($lateLoans),
            'unpaidFines'       => $unpaidFines,
            'paidFines'         => $paidFines,
        ];

        return view('members/show', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return view('members/create', [
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->validate([
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('members/create', $data);
        }

        $uid = sha1(
            $this->request->getVar('first_name')
                . $this->request->getVar('email')
                . $this->request->getVar('phone')
                . rand(0, 1000)
                . md5($this->request->getVar('gender'))
        );

        $qrGenerator = new QRGenerator();
        $qrCodeLabel = $this->request->getVar('first_name')
            . ($this->request->getVar('last_name')
                ? ' ' . $this->request->getVar('last_name') : '');
        $qrCode = $qrGenerator->generateQRCode(
            data: $uid,
            labelText: $qrCodeLabel,
            dir: MEMBERS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->memberModel->save([
            'uid'           => $uid,
            'first_name'    => $this->request->getVar('first_name'),
            'last_name'     => $this->request->getVar('last_name'),
            'email'         => $this->request->getVar('email'),
            'phone'         => $this->request->getVar('phone'),
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $this->request->getVar('gender'),
            'qr_code'       => $qrCode
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('members/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new member successful']);
        return redirect()->to('admin/members');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        $data = [
            'member'     => $member,
            'validation' => \Config\Services::validation(),
        ];

        return view('members/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        if (!$this->validate([
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ])) {
            $data = [
                'member'     => $member,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('members/edit', $data);
        }

        $firstName = $this->request->getVar('first_name');
        $email = $this->request->getVar('email');
        $phone = $this->request->getVar('phone');
        $gender = $this->request->getVar('gender');

        $isChanged = ($firstName != $member['first_name']
            || $email != $member['email']
            || $phone != $member['phone']);

        $uid = $isChanged
            ? sha1($firstName . $email . $phone . rand(0, 1000) . md5($gender))
            : $member['uid'];

        if ($isChanged) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $this->request->getVar('first_name')
                . ($this->request->getVar('last_name')
                    ? ' ' . $this->request->getVar('last_name') : '');
            $qrCode = $qrGenerator->generateQRCode(
                $uid,
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );
            deleteMembersQRCode($member['qr_code']);
        } else {
            $qrCode = $member['qr_code'];
        }

        if (!$this->memberModel->save([
            'id'            => $member['id'],
            'uid'           => $uid,
            'first_name'    => $this->request->getVar('first_name'),
            'last_name'     => $this->request->getVar('last_name'),
            'email'         => $this->request->getVar('email'),
            'phone'         => $this->request->getVar('phone'),
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $this->request->getVar('gender'),
            'qr_code'       => $qrCode
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('members/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update member successful']);
        return redirect()->to('admin/members');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        if (!$this->memberModel->delete($member['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete member', 'error' => true]);
            return redirect()->back();
        }

        deleteMembersQRCode($member['qr_code']);

        session()->setFlashdata(['msg' => 'Member deleted successfully']);
        return redirect()->to('admin/members');
    }
}
