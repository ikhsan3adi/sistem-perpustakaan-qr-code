<?php

namespace App\Controllers\Books;

use App\Models\AuthorModel;
use App\Models\BookModel;
use App\Models\BookStockModel;
use App\Models\LoanModel;
use App\Models\PlaceModel;
use App\Models\PublisherModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class BooksController extends ResourceController
{
    protected BookModel $bookModel;
    protected BookStockModel $bookStockModel;
    protected AuthorModel $authorModel;
    protected PublisherModel $publisherModel;
    protected PlaceModel $placeModel;
    protected LoanModel $loanModel;

    public function __construct()
    {
        $this->bookModel = new BookModel;
        $this->bookStockModel = new BookStockModel;
        $this->authorModel = new AuthorModel;
        $this->publisherModel = new PublisherModel;
        $this->placeModel = new PlaceModel;
        $this->loanModel = new LoanModel;

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
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, publishers.name as publisher, places.name as place')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('authors', 'books.author_id = authors.id', 'LEFT')
                ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
                ->join('places', 'books.place_id = places.id', 'LEFT')
                ->like('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'books');

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });
        } else {
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, publishers.name as publisher, places.name as place')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('authors', 'books.author_id = authors.id', 'LEFT')
                ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
                ->join('places', 'books.place_id = places.id', 'LEFT')
                ->paginate($itemPerPage, 'books');
        }

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search')
        ];

        return view('books/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($slug = null)
    {
        $book = $this->bookModel
            ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, publishers.name as publisher, places.name as place')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('authors', 'books.author_id = authors.id', 'LEFT')
            ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
            ->join('places', 'books.place_id = places.id', 'LEFT')
            ->where('slug', $slug)->first();

        if (empty($book)) {
            throw new PageNotFoundException('Book with slug \'' . $slug . '\' not found');
        }

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

        $bookStock = $book['quantity'] - $loanCount;

        $data = [
            'book'      => $book,
            'loanCount' => $loanCount ?? 0,
            'bookStock' => $bookStock
        ];

        return view('books/show', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $authors = $this->authorModel->findAll();
        $publishers = $this->publisherModel->findAll();
        $places = $this->placeModel->findAll();

        $data = [
            'authors' => $authors,
            'publishers' => $publishers,
            'places' => $places,
            'validation' => \Config\Services::validation(),
        ];

        return view('books/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->validate([
            'cover'     => 'is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[cover,5120]',
            'title'     => 'required|string|max_length[127]',
            'edition'   => 'permit_empty|string|max_length[127]',
            'isbn'      => 'permit_empty|numeric|min_length[10]|max_length[13]',
            'year'      => 'permit_empty|numeric|min_length[4]|max_length[4]|less_than_equal_to[2100]',
            'collation' => 'permit_empty|string|max_length[50]',
            'call_number' => 'permit_empty|string|max_length[50]',
            'language' => 'permit_empty|string|max_length[5]',
            'source'    => 'permit_empty|string|max_length[3]',
            'file_att'  => 'permit_empty|ext_in[file_att,pdf,docx,txt,doc,odf,md,html]',
            'author'    => 'permit_empty|alpha_numeric_punct|max_length[64]',
            'publisher' => 'permit_empty|string|max_length[64]',
            'place'     => 'permit_empty|string|max_length[64]',
            'stock'     => 'permit_empty|numeric|greater_than_equal_to[1]',
        ])) {
            $authors = $this->authorModel->findAll();
            $publishers = $this->publisherModel->findAll();
            $places = $this->placeModel->findAll();

            $data = [
                'authors' => $authors,
                'publishers' => $publishers,
                'places' => $places,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('books/create', $data);
        }

        $coverImage = $this->request->getFile('cover');
        if ($coverImage->getError() != 4) {
            $coverImageFileName = uploadBookCover($coverImage);
        }

        $ebookFile = $this->request->getFile('file_att');
        if ($ebookFile->getError() != 4) {
            dd($ebookFile);
            $ebookFileName = uploadEbook($ebookFile);
        }

        $slug = url_title($this->request->getVar('title') . ' ' . rand(0, 1000), '-', true);

        if (!$this->bookModel->save([
            'slug' => $slug,
            'title' => $this->request->getVar('title'),
            'edition' => $this->request->getVar('edition'),
            'isbn' => $this->request->getVar('isbn'),
            'year' => $this->request->getVar('year'),
            'collation' => $this->request->getVar('collation'),
            'call_number' => $this->request->getVar('call_number'),
            'language_id' => $this->request->getVar('language'),
            'source' => $this->request->getVar('source'),
            'file_att' => $ebookFileName ?? null,
            'author_id' => $this->request->getVar('author'),
            'publisher_id' => $this->request->getVar('publisher'),
            'place_id' => $this->request->getVar('place'),
            'book_cover' => $coverImageFileName ?? null,
        ]) || !$this->bookStockModel->save([
            'book_id' => $this->bookModel->getInsertID(),
            'quantity' => $this->request->getVar('stock') ?? 1
        ])) {
            $authors = $this->authorModel->findAll();
            $publishers = $this->publisherModel->findAll();
            $places = $this->placeModel->findAll();

            $data = [
                'authors' => $authors,
                'publishers' => $publishers,
                'places' => $places,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('books/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new book successful']);
        return redirect()->to('admin/books');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($slug = null)
    {
        $book = $this->bookModel
            ->select('books.*, book_stock.quantity')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->where('slug', $slug)->first();

        if (empty($book)) {
            throw new PageNotFoundException('Book with slug \'' . $slug . '\' not found');
        }

        $authors = $this->authorModel->findAll();
        $publishers = $this->publisherModel->findAll();
        $places = $this->placeModel->findAll();

        $data = [
            'book'       => $book,
            'authors'    => $authors,
            'publishers' => $publishers,
            'places'     => $places,
            'validation' => \Config\Services::validation(),
        ];

        return view('books/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($slug = null)
    {
        $book = $this->bookModel->where('slug', $slug)->first();

        if (empty($book)) {
            throw new PageNotFoundException('Book with slug \'' . $slug . '\' not found');
        }

        if (!$this->validate([
            'cover'     => 'is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[cover,5120]',
            'title'     => 'required|string|max_length[127]',
            'edition'   => 'permit_empty|string|max_length[127]',
            'isbn'      => 'permit_empty|numeric|min_length[10]|max_length[13]',
            'year'      => 'permit_empty|numeric|min_length[4]|max_length[4]|less_than_equal_to[2100]',
            'collation' => 'permit_empty|string|max_length[50]',
            'call_number' => 'permit_empty|string|max_length[50]',
            'language'  => 'permit_empty|string|max_length[5]',
            'source'    => 'permit_empty|string|max_length[3]',
            'file_att'  => 'permit_empty|ext_in[file_att,pdf,docx,txt,doc,odf,md,html]',
            'author'    => 'permit_empty|alpha_numeric_punct|max_length[64]',
            'publisher' => 'permit_empty|string|max_length[64]',
            'place'     => 'permit_empty|alpha_numeric_punct|max_length[64]',
            'stock'     => 'permit_empty|numeric|greater_than_equal_to[1]',
        ])) {
            $authors = $this->authorModel->findAll();
            $publishers = $this->publisherModel->findAll();
            $places = $this->placeModel->findAll();

            $data = [
                'book'       => $book,
                'authors'    => $authors,
                'publishers' => $publishers,
                'places'     => $places,
                'validation' => \Config\Services::validation(),
            ];

            return view('books/edit', $data);
        }

        $bookStock = $this->bookStockModel->where('book_id', $book['id'])->first();

        // SAVE COVER IMAGE
        $coverImage = $this->request->getFile('cover');

        if ($coverImage->getError() == 4) {
            $coverImageFileName = $book['book_cover'];
        } else {
            $coverImageFileName = updateBookCover(
                newCoverImage: $coverImage,
                formerCoverImageFileName: $book['book_cover']
            );
        }

        // SAVE EBOOK
        $ebook = $this->request->getFile('file_att');

        if ($ebook->getError() == 4) {
            $ebookFileName = $book['file_att'];
        } else {
            $ebookFileName = updateEbook(
                newEbook: $ebook,
                formerEbookFileName: $book['file_att']
            );
        }

        $slug = $this->request->getVar('title') != $book['title']
            ? url_title($this->request->getVar('title') . ' ' . rand(0, 1000), '-', true)
            : $book['slug'];

        if (!$this->bookModel->save([
            'id'  => $book['id'],
            'slug' => $slug,
            'title' => $this->request->getVar('title'),
            'edition' => $this->request->getVar('edition'),
            'isbn' => $this->request->getVar('isbn'),
            'year' => $this->request->getVar('year'),
            'collation' => $this->request->getVar('collation'),
            'call_number' => $this->request->getVar('call_number'),
            'language_id' => $this->request->getVar('language'),
            'source' => $this->request->getVar('source'),
            'author_id' => $this->request->getVar('author'),
            'publisher_id' => $this->request->getVar('publisher'),
            'place_id' => $this->request->getVar('place'),
            'book_cover' => $coverImageFileName ?? null,
            'file_att' => $ebookFileName ?? null,
        ]) || !$this->bookStockModel->save([
            'id' => $bookStock['id'],
            'book_id' => $book['id'],
            'quantity' => $this->request->getVar('stock') ?? 1
        ])) {
            $authors = $this->authorModel->findAll();
            $publishers = $this->publisherModel->findAll();
            $places = $this->placeModel->findAll();

            $data = [
                'book'       => $book,
                'authors'    => $authors,
                'publishers' => $publishers,
                'places'     => $places,
                'validation' => \Config\Services::validation(),
            ];

            session()->setFlashdata(['msg' => 'Update failed']);
            return view('books/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update book successful']);
        return redirect()->to('admin/books');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($slug = null)
    {
        $book = $this->bookModel->where('slug', $slug)->first();

        if (empty($book)) {
            throw new PageNotFoundException('Book with slug \'' . $slug . '\' not found');
        }

        $bookStock = $this->bookStockModel->where('book_id', $book['id'])->first();

        if (!$this->bookModel->delete($book['id']) || !$this->bookStockModel->delete($bookStock['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete book', 'error' => true]);
            return redirect()->back();
        }

        // delete former image file
        deleteBookCover($book['book_cover']);

        session()->setFlashdata(['msg' => 'Book deleted successfully']);
        return redirect()->to('admin/books');
    }
}
