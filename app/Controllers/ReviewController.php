<?php

namespace App\Controllers;

class ReviewController extends BaseController
{
    public function submit(string $slug)
    {
        $db      = \Config\Database::connect();
        $vehicle = $db->table('vehicles v')
            ->select('v.id, v.name, v.slug, v.image_url, b.name AS brand_name')
            ->join('brands b', 'b.id = v.brand_id', 'left')
            ->where('v.slug', $slug)
            ->where('v.status', 'published')
            ->get()->getRowArray();

        if (!$vehicle) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->render('reviews/submit', [
            'vehicle'          => $vehicle,
            'meta_title'       => 'Write a Review — ' . $vehicle['name'] . ' | Charj.in',
            'meta_description' => 'Share your ownership experience of the ' . $vehicle['name'] . '. Help other buyers make the right EV decision.',
        ]);
    }

    public function store()
    {
        $db      = \Config\Database::connect();
        $id      = (int) $this->request->getPost('vehicle_id');
        $vehicle = $db->table('vehicles')->where('id', $id)->where('status', 'published')->get()->getRowArray();

        if (!$vehicle) {
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle not found.');
        }

        $rating = (float) $this->request->getPost('rating');
        if ($rating < 1 || $rating > 5) {
            return redirect()->back()->withInput()->with('review_error', 'Please select a rating.');
        }

        $name = trim($this->request->getPost('reviewer_name') ?? '');
        if (strlen($name) < 2) {
            return redirect()->back()->withInput()->with('review_error', 'Please enter your name.');
        }

        $content = trim($this->request->getPost('content') ?? '');
        if (strlen($content) < 30) {
            return redirect()->back()->withInput()->with('review_error', 'Review must be at least 30 characters.');
        }

        $db->table('reviews')->insert([
            'vehicle_id'       => $id,
            'reviewer_name'    => $name,
            'reviewer_city'    => trim($this->request->getPost('reviewer_city') ?? ''),
            'rating'           => $rating,
            'title'            => trim($this->request->getPost('title') ?? ''),
            'content'          => $content,
            'pros'             => trim($this->request->getPost('pros') ?? ''),
            'cons'             => trim($this->request->getPost('cons') ?? ''),
            'ownership_months' => (int) ($this->request->getPost('ownership_months') ?: 0) ?: null,
            'km_driven'        => (int) ($this->request->getPost('km_driven') ?: 0) ?: null,
            'verified_purchase'=> 0,
            'status'           => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('vehicles/' . $vehicle['slug'] . '#tab-reviews'))
            ->with('review_success', '✅ Your review has been submitted and will appear after moderation. Thank you!');
    }
}
