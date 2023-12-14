<?php

namespace App\Controllers\Users;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class UsersController extends ResourceController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $itemPerPage = 20;

        $users = $this->userModel->withIdentities()->paginate($itemPerPage, 'users');

        $data = [
            'users'             => $users,
            'pager'             => $this->userModel->pager,
            'currentPage'       => $this->request->getVar('page_users') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('users/index', $data);
    }


    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $user = $this->userModel->withIdentities()->find($id);

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        $data = [
            'user'           => $user,
            'validation'     => \Config\Services::validation(),
        ];

        return view('users/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $user = $this->userModel->withIdentities()->find($id);

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        $username = $user->toArray()['username'];

        $usernameChanged = $username != $this->request->getVar('username');

        if (!$this->validate([
            'username'      => $usernameChanged ? 'required|string|is_unique[users.username]' : 'required|string',
            'email'         => 'required|valid_email|max_length[255]',
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'permit_empty|' . Passwords::getMaxLengthRule() . '|strong_password',
                'errors' => [
                    'max_byte' => 'Auth.errorPasswordTooLongBytes',
                ],
            ],
            'password_confirm' => [
                'label' => 'Auth.passwordConfirm',
                'rules' => 'permit_empty|matches[password]',
            ],
        ])) {
            $data = [
                'user'       => $user,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('users/edit', $data);
        }

        if (!$this->userModel->save(new User([
            'id'       => $id,
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password') ?? null,
        ]))) {
            $data = [
                'user'     => $user,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('users/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update user successful']);
        return redirect()->to('admin/users');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $user = $this->userModel->where('id', $id)->first();

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        if (!$this->userModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete user', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'User deleted successfully']);
        return redirect()->to('admin/users');
    }
}
