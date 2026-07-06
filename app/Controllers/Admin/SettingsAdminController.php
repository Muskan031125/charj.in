<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class SettingsAdminController extends AdminBaseController
{

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    // ---------------------------------------------------------------
    // Index ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â display all settings grouped by group column
    // ---------------------------------------------------------------

    public function index()
    {
        $rows = $this->db->table('settings')
            ->orderBy('`group`', 'ASC')
            ->orderBy('`key`', 'ASC')
            ->get()
            ->getResultArray();

        // Group settings into associative array: ['group' => [setting, ...]]
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['group']][] = $row;
        }

        // Flatten to key=>value map for the view
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return view('admin/settings/index', [
            'grouped'  => $grouped,
            'settings' => $settings,
        ]);
    }

    // ---------------------------------------------------------------
    // Save ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â upsert all posted settings
    // ---------------------------------------------------------------

    public function save()
    {
        $post = $this->request->getPost();

        // Expected POST format: settings[key] = value
        // or a flat array of key => value pairs if group is embedded in key
        $settings = $post['settings'] ?? $post;

        // Remove CSRF token and any other non-setting keys
        $skip = ['csrf_test_name', csrf_token(), '_method'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $skip)) {
                continue;
            }

            // Try to find existing row
            $existing = $this->db->table('settings')->where('`key`', $key)->get()->getRowArray();

            if ($existing) {
                $this->db->table('settings')->where('`key`', $key)->update([
                    'value'      => $value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Attempt to derive group from key prefix (e.g. "site_title" -> group "site")
                $parts = explode('_', $key, 2);
                $group = count($parts) > 1 ? $parts[0] : 'general';

                $this->db->table('settings')->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'group'      => $group,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'Settings saved successfully.');
    }
}
