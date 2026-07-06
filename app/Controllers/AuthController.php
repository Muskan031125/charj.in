<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // ---------------------------------------------------------------
    // Login
    // ---------------------------------------------------------------

    public function login()
    {
        if (session()->get('user_logged_in')) {
            return redirect()->to(site_url('/'));
        }

        $data          = $this->globalData;
        $data['title'] = 'Login — Charj.in';

        return view('auth/login', $data);
    }

    public function loginPost()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return redirect()->back()->with('error', 'Please enter your email and password.');
        }

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account has been suspended.');
        }

        $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Admin → set admin session and go to dashboard
        if (($user['role'] ?? 'customer') === 'admin') {
            session()->set([
                'admin_logged_in' => true,
                'admin_id'        => $user['id'],
                'admin_name'      => $user['name'],
                'admin_email'     => $user['email'],
                'admin_role'      => 'admin',
            ]);
            return redirect()->to(site_url('admin/dashboard'))
                ->with('success', 'Welcome back, ' . $user['name'] . '!');
        }

        // Customer → set user session
        session()->set([
            'user_logged_in' => true,
            'user_id'        => $user['id'],
            'user_name'      => $user['name'],
            'user_email'     => $user['email'],
        ]);

        $redirect = session()->getFlashdata('redirect_after_login') ?? site_url('/');

        return redirect()->to($redirect)->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    // ---------------------------------------------------------------
    // Register
    // ---------------------------------------------------------------

    public function register()
    {
        if (session()->get('user_logged_in')) {
            return redirect()->to(site_url('/'));
        }

        $data          = $this->globalData;
        $data['title'] = 'Create Account — Charj.in';

        return view('auth/register', $data);
    }

    public function registerPost()
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[100]',
            'email'    => 'required|valid_email|max_length[150]',
            'phone'    => 'required|min_length[10]|max_length[15]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($this->request->getPost('email'))) {
            return redirect()->back()->withInput()
                ->with('error', 'An account with this email already exists. Please log in.');
        }

        $userId = $userModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'phone'         => $this->request->getPost('phone'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'city'          => $this->request->getPost('city') ?? '',
            'role'          => 'customer',
            'status'        => 'active',
        ]);

        session()->set([
            'user_logged_in' => true,
            'user_id'        => $userId,
            'user_name'      => $this->request->getPost('name'),
            'user_email'     => $this->request->getPost('email'),
        ]);

        return redirect()->to(site_url('profile'))
            ->with('success', 'Welcome to Charj.in! Your account is ready.');
    }

    // ---------------------------------------------------------------
    // Logout
    // ---------------------------------------------------------------

    public function logout()
    {
        session()->remove(['user_logged_in', 'user_id', 'user_name', 'user_email']);

        return redirect()->to(site_url('/'))->with('success', 'You have been logged out.');
    }

    // ---------------------------------------------------------------
    // Profile / Dashboard
    // ---------------------------------------------------------------

    public function profile()
    {
        $userId    = session()->get('user_id');
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        $savedIds      = $userModel->getSavedVehicles($userId);
        $savedVehicles = [];

        if (!empty($savedIds)) {
            $savedVehicles = (new \App\Models\VehicleModel())
                ->withBrandCategory()
                ->whereIn('vehicles.id', $savedIds)
                ->findAll();
        }

        // Recently viewed vehicles
        $recentlyViewed = [];
        try {
            if ($this->db->tableExists('user_activity')) {
                $viewedRows = $this->db->table('user_activity')
                    ->select('entity_id')
                    ->where('user_id', $userId)
                    ->where('action', 'view_vehicle')
                    ->orderBy('created_at', 'DESC')
                    ->limit(20)
                    ->get()->getResultArray();
                $viewedIds = array_unique(array_column($viewedRows, 'entity_id'));
                $viewedIds = array_slice($viewedIds, 0, 6);
                if (!empty($viewedIds)) {
                    $recentlyViewed = (new \App\Models\VehicleModel())
                        ->withBrandCategory()
                        ->whereIn('vehicles.id', $viewedIds)
                        ->limit(6)->findAll();
                }
            }
        } catch (\Exception $e) { $recentlyViewed = []; }

        // Last quiz result
        $quizResult = null;
        try {
            if ($this->db->tableExists('user_activity')) {
                $quizRow = $this->db->table('user_activity')
                    ->where('user_id', $userId)
                    ->where('action', 'quiz_complete')
                    ->orderBy('created_at', 'DESC')
                    ->limit(1)->get()->getRowArray();
                if ($quizRow && $quizRow['metadata']) {
                    $quizResult = json_decode($quizRow['metadata'], true);
                }
            }
        } catch (\Exception $e) {}

        // View count
        $viewCount = 0;
        try {
            if ($this->db->tableExists('user_activity')) {
                $viewCount = $this->db->table('user_activity')
                    ->where('user_id', $userId)->where('action', 'view_vehicle')
                    ->countAllResults();
            }
        } catch (\Exception $e) {}

        $data                    = $this->globalData;
        $data['title']           = 'My Account — Charj.in';
        $data['user']            = $user;
        $data['savedVehicles']   = $savedVehicles;
        $data['recentlyViewed']  = $recentlyViewed;
        $data['quizResult']      = $quizResult;
        $data['viewCount']       = $viewCount;

        return view('auth/profile', $data);
    }

    // ---------------------------------------------------------------
    // Save / unsave a vehicle (AJAX or redirect)
    // ---------------------------------------------------------------

    public function saveVehicle(int $vehicleId)
    {
        if (!session()->get('user_logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please log in']);
            }
            return redirect()->to(site_url('login'));
        }

        $userModel = new UserModel();
        $added     = $userModel->toggleSavedVehicle(session()->get('user_id'), $vehicleId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'saved' => $added]);
        }

        return redirect()->back()
            ->with('success', $added ? 'Vehicle saved!' : 'Vehicle removed from saved.');
    }
}
