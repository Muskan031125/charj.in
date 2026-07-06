<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Single login page for everyone
        return redirect()->to(site_url('login'));
    }

    public function attempt()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)
                          ->where('status', 'active')
                          ->first();

        $hash = $user['password_hash'] ?? $user['password'] ?? '';
        if (!$user || !password_verify($password, $hash)) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        if (($user['role'] ?? '') !== 'admin') {
            return redirect()->back()->withInput()->with('error', 'Access denied.');
        }

        session()->set([
            'admin_logged_in' => true,
            'admin_id'        => $user['id'],
            'admin_name'      => $user['name'],
            'admin_email'     => $user['email'],
            'admin_role'      => $role,
        ]);

        return redirect()->to(site_url('admin'))->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'You have been logged out successfully.');
    }

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }
}
