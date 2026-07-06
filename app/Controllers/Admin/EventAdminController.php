<?php

namespace App\Controllers\Admin;

class EventAdminController extends AdminBaseController
{
    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        return trim($slug, '-');
    }

    private function uniqueSlug(string $base, int $excludeId = 0): string
    {
        $slug    = $base;
        $counter = 1;
        while (true) {
            $builder = $this->db->table('events')->where('slug', $slug);
            if ($excludeId > 0) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                break;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $builder = $this->db->table('events');
        $total    = $builder->countAllResults(false);
        $events = $builder->orderBy('start_date', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        return view('admin/events/index', [
            'events'     => $events,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'total'      => $total,
        ]);
    }

    public function create()
    {
        return view('admin/events/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $title = trim($post['title'] ?? '');
        $description = trim($post['description'] ?? '');
        $startDate = trim($post['start_date'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Event title is required.';
        }
        if (empty($description)) {
            $errors['description'] = 'Description is required.';
        }
        if (empty($startDate)) {
            $errors['start_date'] = 'Start date is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug = $this->generateSlug($title);
        $slug = $this->uniqueSlug($slug);
        $status = $post['status'] ?? 'draft';

        $now = date('Y-m-d H:i:s');
        $this->db->table('events')->insert([
            'title'            => $title,
            'slug'             => $slug,
            'description'      => $description,
            'event_type'       => $post['event_type'] ?? 'other',
            'start_date'       => $startDate,
            'end_date'         => $post['end_date'] ?? null,
            'city'             => $post['city'] ?? null,
            'venue_address'    => $post['venue_address'] ?? null,
            'organizer'        => $post['organizer'] ?? null,
            'registration_url' => $post['registration_url'] ?? null,
            'banner_image'     => $post['banner_image'] ?? null,
            'is_featured'      => $post['is_featured'] ?? 0,
            'status'           => $status,
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        return redirect()->to('/admin/events')->with('success', 'Event created successfully.');
    }

    public function edit(int $id)
    {
        $event = $this->db->table('events')->where('id', $id)->get()->getRowArray();
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event not found.');
        }

        return view('admin/events/edit', ['event' => $event]);
    }

    public function update(int $id)
    {
        $event = $this->db->table('events')->where('id', $id)->get()->getRowArray();
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $title = trim($post['title'] ?? '');
        $description = trim($post['description'] ?? '');
        $startDate = trim($post['start_date'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Event title is required.';
        }
        if (empty($description)) {
            $errors['description'] = 'Description is required.';
        }
        if (empty($startDate)) {
            $errors['start_date'] = 'Start date is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }
        $slug   = $this->uniqueSlug($slug, $id);
        $status = $post['status'] ?? 'draft';
        $now    = date('Y-m-d H:i:s');

        $this->db->table('events')->where('id', $id)->update([
            'title'            => $title,
            'slug'             => $slug,
            'description'      => $description,
            'event_type'       => $post['event_type'] ?? 'other',
            'start_date'       => $startDate,
            'end_date'         => $post['end_date'] ?? null,
            'city'             => $post['city'] ?? null,
            'venue_address'    => $post['venue_address'] ?? null,
            'organizer'        => $post['organizer'] ?? null,
            'registration_url' => $post['registration_url'] ?? null,
            'banner_image'     => $post['banner_image'] ?? null,
            'is_featured'      => $post['is_featured'] ?? 0,
            'status'           => $status,
            'updated_at'       => $now,
        ]);

        return redirect()->to('/admin/events')->with('success', 'Event updated successfully.');
    }

    public function delete(int $id)
    {
        $this->db->table('events')->where('id', $id)->delete();
        return redirect()->to('/admin/events')->with('success', 'Event deleted successfully.');
    }
}
