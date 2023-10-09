<?php

namespace App\Controllers\Books;

use App\Models\AuthorModel;
use App\Models\BookModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class AuthorsController extends ResourceController
{
    protected AuthorModel $authorModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->authorModel = new AuthorModel;
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

        $authors = $this->authorModel->paginate($itemPerPage, 'authors');

        $bookCountInAuthors = [];

        foreach ($authors as $author) {
            array_push($bookCountInAuthors, $this->bookModel
                ->where('author_id', $author['id'])
                ->countAllResults());
        }

        $data = [
            'authors'        => $authors,
            'bookCountInAuthors' => $bookCountInAuthors,
            'pager'             => $this->authorModel->pager,
            'currentPage'       => $this->request->getVar('page_authors') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('authors/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $author = $this->authorModel->where('id', $id)->first();

        if (empty($author)) {
            throw new PageNotFoundException('Author not found');
        }

        $itemPerPage = 20;

        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, authors.authority_type, publishers.name as publisher, places.name as place')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('authors', 'books.author_id = authors.id', 'LEFT')
            ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
            ->join('places', 'books.place_id = places.id', 'LEFT')
            ->where('author_id', $id)
            ->paginate($itemPerPage, 'books');

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'author'        => $this->authorModel
                ->select('authors.name')
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
        return view('authors/create', [
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
            'author'    => 'required|string|min_length[2]',
            'year'    => 'permit_empty|string|min_length[2]',
            'authority_type' => 'permit_empty|string',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('authors/create', $data);
        }

        if (!$this->authorModel->save([
            'name' => $this->request->getVar('author'),
            'year' => $this->request->getVar('year'),
            'authority_type' => $this->request->getVar('authority_type') ?? 1,
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('authors/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new author successful']);
        return redirect()->to('admin/authors');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $author = $this->authorModel->where('id', $id)->first();

        if (empty($author)) {
            throw new PageNotFoundException('Author not found');
        }

        $data = [
            'author'         => $author,
            'validation'     => \Config\Services::validation(),
        ];

        return view('authors/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $author = $this->authorModel->where('id', $id)->first();

        if (empty($author)) {
            throw new PageNotFoundException('Author not found');
        }

        if (!$this->validate([
            'author'    => 'required|string|min_length[2]',
            'year'    => 'permit_empty|string|min_length[2]',
            'authority_type' => 'permit_empty|string',
        ])) {
            $data = [
                'author'     => $author,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('authors/edit', $data);
        }

        if (!$this->authorModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('author'),
            'year' => $this->request->getVar('year'),
            'authority_type' => $this->request->getVar('authority_type'),
        ])) {
            $data = [
                'author'     => $author,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('authors/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update author successful']);
        return redirect()->to('admin/authors');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $author = $this->authorModel->where('id', $id)->first();

        if (empty($author)) {
            throw new PageNotFoundException('Author not found');
        }

        if ($this->bookModel->where('author_id', $id)->countAllResults() > 0) {
            session()->setFlashdata([
                'msg' => "Failed to delete author, book with author '{$author['name']}' must be empty",
                'error' => true
            ]);
            return redirect()->back();
        }

        if (!$this->authorModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete author', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Author deleted successfully']);
        return redirect()->to('admin/authors');
    }
}
