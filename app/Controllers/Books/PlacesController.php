<?php

namespace App\Controllers\Books;

use App\Models\BookModel;
use App\Models\PlaceModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class PlacesController extends ResourceController
{
    protected PlaceModel $placeModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->placeModel = new PlaceModel;
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

        $places = $this->placeModel->paginate($itemPerPage, 'places');

        $bookCountInPublisherPlaces = [];

        foreach ($places as $place) {
            array_push($bookCountInPublisherPlaces, $this->bookModel
                ->where('place_id', $place['id'])
                ->countAllResults());
        }

        $data = [
            'places'        => $places,
            'bookCountInPublisherPlaces' => $bookCountInPublisherPlaces,
            'pager'             => $this->placeModel->pager,
            'currentPage'       => $this->request->getVar('page_places') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('places/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $place = $this->placeModel->where('id', $id)->first();

        if (empty($place)) {
            throw new PageNotFoundException('Publish place not found');
        }

        $itemPerPage = 20;

        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, authors.name as author, authors.year as author_year, authors.authority_type, publishers.name as publisher, places.name as place')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('authors', 'books.author_id = authors.id', 'LEFT')
            ->join('publishers', 'books.publisher_id = publishers.id', 'LEFT')
            ->join('places', 'books.place_id = places.id', 'LEFT')
            ->where('place_id', $id)
            ->paginate($itemPerPage, 'books');

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'place'     => $this->placeModel
                ->select('places.name')
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
        return view('places/create', [
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
            'place'    => 'required|string|min_length[2]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('places/create', $data);
        }

        if (!$this->placeModel->save([
            'name' => $this->request->getVar('place')
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('places/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new place successful']);
        return redirect()->to('admin/places');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $place = $this->placeModel->where('id', $id)->first();

        if (empty($place)) {
            throw new PageNotFoundException('Publish place not found');
        }

        $data = [
            'place'      => $place,
            'validation'     => \Config\Services::validation(),
        ];

        return view('places/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $place = $this->placeModel->where('id', $id)->first();

        if (empty($place)) {
            throw new PageNotFoundException('Publish place not found');
        }

        if (!$this->validate([
            'place'    => 'required|string|min_length[2]',
        ])) {
            $data = [
                'place'  => $place,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('places/edit', $data);
        }

        if (!$this->placeModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('place')
        ])) {
            $data = [
                'place'  => $place,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('places/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update place successful']);
        return redirect()->to('admin/places');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $place = $this->placeModel->where('id', $id)->first();

        if (empty($place)) {
            throw new PageNotFoundException('Publish place not found');
        }

        if ($this->bookModel->where('place_id', $id)->countAllResults() > 0) {
            session()->setFlashdata([
                'msg' => "Failed to delete place, book with place '{$place['name']}' must be empty",
                'error' => true
            ]);
            return redirect()->back();
        }

        if (!$this->placeModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete place', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Publish place deleted successfully']);
        return redirect()->to('admin/places');
    }
}
