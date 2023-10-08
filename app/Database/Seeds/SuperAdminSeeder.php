<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class SuperAdminSeeder extends Seeder
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function run()
    {
        $superAdminUser = new User([
            'username' => 'superadmin',
            'email'    => 'superadmin@admin.com',
            'password' => 'superadmin',
        ]);

        $this->userModel->save($superAdminUser);

        // To get the complete user object with ID, we need to get from the database
        $user = $this->userModel->findById($this->userModel->getInsertID());

        // Add user to superadmin group
        $user->addGroup('superadmin');

        // Activate user
        $user->activate();
    }
}
