<?php

namespace App\Controllers\Admin;

class UsersAdminController extends AdminBaseController
{
    public function index()
    {
        $search  = $this->request->getGet('q');
        $builder = $this->db->table('users')->orderBy('created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
        }

        $users = $builder->get()->getResultArray();

        // Attach activity counts
        if ($this->db->tableExists('user_activity')) {
            foreach ($users as &$u) {
                $u['activity_count'] = $this->db->table('user_activity')
                    ->where('user_id', $u['id'])
                    ->countAllResults();
            }
            unset($u);
        } else {
            foreach ($users as &$u) {
                $u['activity_count'] = 0;
            }
            unset($u);
        }

        return view('admin/users/index', [
            'page_title' => 'Users',
            'users'      => $users,
            'search'     => $search,
        ]);
    }

    public function show($id)
    {
        $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $activity = [];
        $savedCount = 0;
        if ($this->db->tableExists('user_activity')) {
            $activity = $this->db->table('user_activity')
                ->where('user_id', $id)
                ->orderBy('created_at', 'DESC')
                ->limit(20)
                ->get()
                ->getResultArray();
            $savedCount = $this->db->table('user_activity')
                ->where('user_id', $id)
                ->where('action', 'view_vehicle')
                ->countAllResults();
        }

        return view('admin/users/show', [
            'page_title' => 'User: ' . ($user['name'] ?? 'Unknown'),
            'user'       => $user,
            'activity'   => $activity,
            'savedCount' => $savedCount,
        ]);
    }

    public function delete($id)
    {
        $this->db->table('users')->where('id', $id)->delete();
        return redirect()->to('/admin/users')->with('success', 'User deleted.');
    }
}
