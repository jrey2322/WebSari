<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Manage Staff',
            'users' => $this->userModel->orderBy('created_at','DESC')->findAll(),
        ];
        return view('users/index', $data);
    }

    public function store()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $rules = [
            'name'     => 'required|min_length[2]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
                              $this->request->getPost('password'),
                              PASSWORD_DEFAULT),
            'role'     => 'staff',
            'phone'    => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/users')
                         ->with('success', 'Staff account created!');
    }

    public function toggle(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        // Cannot deactivate yourself
        if ($id === (int)$this->session->get('user_id')) {
            return redirect()->to('/users')
                             ->with('error', 'You cannot deactivate your own account.');
        }

        $user = $this->userModel->find($id);
        if ($user) {
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            $this->userModel->update($id, ['status' => $newStatus]);
        }

        return redirect()->to('/users')
                         ->with('success', 'Staff status updated.');
    }

    public function delete(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        if ($id === (int)$this->session->get('user_id')) {
            return redirect()->to('/users')
                             ->with('error', 'You cannot delete your own account.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'Staff removed.');
    }
}