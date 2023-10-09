<?php

namespace App\Controllers;

use App\Models\BookModel;

class Home extends BaseController
{
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel;
    }

    public function index(): string
    {
        return view('home/home');
    }

    public function book(): string
    {
        $itemPerPage = 20;

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->like('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'books');

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });
        } else {
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->paginate($itemPerPage, 'books');
        }

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search')
        ];

        return view('home/book', $data);
    }
}
