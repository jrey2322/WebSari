<?php


namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // ── Login Page ────────────────────────────
    public function login()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    // ── Authenticate ──────────────────────────
    public function authenticate()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $user  = $model->findByEmail($this->request->getPost('email'));

        if (!$user || $user['status'] === 'inactive') {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Account not found or deactivated.');
        }

        if (!password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Incorrect password.');
        }

        // Set session
        $this->session->set([
            'logged_in' => true,
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
            'user_email'=> $user['email'],
        ]);

        return redirect()->to('/dashboard')
                         ->with('success', 'Welcome back, ' . $user['name'] . '! 👋');
    }

    // ── Register Page ─────────────────────────
    public function register()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    // ── Process Registration ──────────────────
    public function registerStore()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'This email is already registered.'
            ],
            'confirm_password' => [
                'matches' => 'Passwords do not match.'
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $role  = $this->request->getPost('role') ?? 'staff';

        // Only allow owner registration if no owner exists yet
        if ($role === 'owner') {
            $existingOwner = $model->where('role', 'owner')->first();
            if ($existingOwner) {
                $role = 'staff'; // Downgrade to staff
            }
        }

        $model->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $role,
            'phone'    => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/login')
                         ->with('success', 'Account created! You can now login.');
    }

    // ── Logout ────────────────────────────────
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')
                         ->with('success', 'Logged out successfully. Goodbye! 👋');
    }
}