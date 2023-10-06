<?php

namespace App\Controllers\Books;

use App\Models\BookModel;
use App\Models\PublisherModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class PublishersController extends ResourceController
{
    protected PublisherModel $publisherModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->publisherModel = new PublisherModel;
        $this->bookModel = new BookModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $itemPerPage = 20;

        $publishers = $this->publisherModel->paginate($itemPerPage, 'publishers');

        $bookCountInPublishers = [];

        foreach ($publishers as $publisher) {
            array_push($bookCountInPublishers, $this->bookModel
                ->where('publisher_id', $publisher['id'])
                ->countAllResults());
        }

        $data = [
            'publishers'        => $publishers,
            'bookCountInPublishers' => $bookCountInPublishers,
            'pager'             => $this->publisherModel->pager,
            'currentPage'       => $this->request->getVar('page_publishers') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('publishers/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $publisher = $this->publisherModel->where('id', $id)->first();

        if (empty($publisher)) {
            throw new PageNotFoundException('Publisher not found');
        }

        $itemPerPage = 20;

        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, authors.authority_type, publishers.name as publisher, places.name as place')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('authors', 'books.author_id = authors.id', 'LEFT')
            ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
            ->join('places', 'books.place_id = places.id', 'LEFT')
            ->where('publisher_id', $id)
            ->paginate($itemPerPage, 'books');

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'publisher'     => $this->publisherModel
                ->select('publishers.name')
                ->where('id', $id)->first()['name']
        ];

        return view('books/index', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return view('publishers/create', [
            'validation' => \Config\Services::validation(),
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
            'publisher'    => 'required|string|min_length[2]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('publishers/create', $data);
        }

        if (!$this->publisherModel->save([
            'name' => $this->request->getVar('publisher')
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('publishers/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new publisher successful']);
        return redirect()->to('admin/publishers');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $publisher = $this->publisherModel->where('id', $id)->first();

        if (empty($publisher)) {
            throw new PageNotFoundException('Publisher not found');
        }

        $data = [
            'publisher'      => $publisher,
            'validation'     => \Config\Services::validation(),
        ];

        return view('publishers/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $publisher = $this->publisherModel->where('id', $id)->first();

        if (empty($publisher)) {
            throw new PageNotFoundException('Publisher not found');
        }

        if (!$this->validate([
            'publisher'    => 'required|string|min_length[2]',
        ])) {
            $data = [
                'publisher'  => $publisher,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('publishers/edit', $data);
        }

        if (!$this->publisherModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('publisher')
        ])) {
            $data = [
                'publisher'  => $publisher,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('publishers/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update publisher successful']);
        return redirect()->to('admin/publishers');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $publisher = $this->publisherModel->where('id', $id)->first();

        if (empty($publisher)) {
            throw new PageNotFoundException('Publisher not found');
        }

        if ($this->bookModel->where('publisher_id', $id)->countAllResults() > 0) {
            session()->setFlashdata([
                'msg' => "Failed to delete publisher, book with publisher '{$publisher['name']}' must be empty",
                'error' => true
            ]);
            return redirect()->back();
        }

        if (!$this->publisherModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete publisher', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Publisher deleted successfully']);
        return redirect()->to('admin/publishers');
    }
}
