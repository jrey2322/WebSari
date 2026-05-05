<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Already logged in
        if ($this->session->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        // Basic validation
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->to(base_url('login'))
                             ->withInput()
                             ->with('error', 'Email and password are required.');
        }

        // Find user
        $model = new UserModel();
        $user  = $model->where('email', $email)->first();

        // Check if user exists
        if (!$user) {
            return redirect()->to(base_url('login'))
                             ->withInput()
                             ->with('error', 'No account found with that email.');
        }

        // Check if active
        if ($user['status'] === 'inactive') {
            return redirect()->to(base_url('login'))
                             ->withInput()
                             ->with('error', 'Your account has been deactivated.');
        }

        // Check password
        if (!password_verify($password, $user['password'])) {
            return redirect()->to(base_url('login'))
                             ->withInput()
                             ->with('error', 'Incorrect password. Please try again.');
        }

        // ✅ Login success - set session
        $this->session->set([
            'logged_in'  => true,
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_role'  => $user['role'],
            'user_email' => $user['email'],
        ]);

        return redirect()->to(base_url('dashboard'))
                         ->with('success', 'Welcome back, ' . $user['name'] . '! 👋');
    }

    public function register()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register');
    }

    public function registerStore()
    {
        $name            = trim($this->request->getPost('name'));
        $email           = trim($this->request->getPost('email'));
        $password        = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $role            = $this->request->getPost('role') ?? 'staff';
        $phone           = trim($this->request->getPost('phone') ?? '');

        // Validate manually
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Full name is required.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required.';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        // Check email unique
        $model = new UserModel();
        $existing = $model->where('email', $email)->first();
        if ($existing) {
            $errors[] = 'This email is already registered.';
        }

        if (!empty($errors)) {
            return redirect()->to(base_url('register'))
                             ->withInput()
                             ->with('errors', $errors);
        }

        // Only allow one owner
        if ($role === 'owner') {
            $ownerExists = $model->where('role', 'owner')->first();
            if ($ownerExists) {
                $role = 'staff';
            }
        }

        // Insert
        $model->insert([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role,
            'phone'    => $phone,
            'status'   => 'active',
        ]);

        return redirect()->to(base_url('login'))
                         ->with('success', 'Account created successfully! You can now login. 🎉');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'))
                         ->with('success', 'Logged out successfully. Goodbye! 👋');
    }
}