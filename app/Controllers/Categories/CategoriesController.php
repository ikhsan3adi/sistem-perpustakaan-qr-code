<?php

namespace App\Controllers\Categories;

use App\Models\BookModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class CategoriesController extends ResourceController
{
    protected CategoryModel $categoryModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel;
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

        $categories = $this->categoryModel->paginate($itemPerPage, 'categories');

        $bookCountInCategories = [];

        foreach ($categories as $category) {
            array_push($bookCountInCategories, $this->bookModel
                ->where('category_id', $category['id'])
                ->countAllResults());
        }

        $data = [
            'categories'        => $categories,
            'bookCountInCategories' => $bookCountInCategories,
            'pager'             => $this->categoryModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('categories/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return view('categories/create', [
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
            'category'  => 'required|string|min_length[2]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('categories/create', $data);
        }

        if (!$this->categoryModel->save([
            'name' => $this->request->getVar('category'),
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('categories/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new category successful']);
        return redirect()->to('admin/categories');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        $data = [
            'category'       => $category,
            'validation'     => \Config\Services::validation(),
        ];

        return view('categories/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->validate([
            'category'  => 'required|string|min_length[2]',
        ])) {
            $data = [
                'category'   => $category,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('categories/edit', $data);
        }

        if (!$this->categoryModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('category'),
        ])) {
            $data = [
                'category'   => $category,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('categories/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update category successful']);
        return redirect()->to('admin/categories');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->categoryModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete category', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Category deleted successfully']);
        return redirect()->to('admin/categories');
    }
}
