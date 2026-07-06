<?php

namespace App\Controllers\Admin;

class ReviewsAdminController extends AdminBaseController
{
    public function index()
    {
        $status = $this->request->getGet('status') ?? 'pending';

        $reviews = $this->db->query("
            SELECT r.*, v.name as vehicle_name
            FROM reviews r
            LEFT JOIN vehicles v ON v.id = r.vehicle_id
            WHERE r.status = ?
            ORDER BY r.created_at DESC
        ", [$status])->getResultArray();

        $counts = [];
        foreach (['pending', 'published', 'rejected'] as $s) {
            $counts[$s] = $this->db->table('reviews')->where('status', $s)->countAllResults();
        }

        return view('admin/reviews/index', [
            'page_title' => 'Reviews',
            'reviews'    => $reviews,
            'status'     => $status,
            'counts'     => $counts,
        ]);
    }

    public function approve($id)
    {
        $this->db->table('reviews')->where('id', $id)->update(['status' => 'published']);
        return redirect()->back()->with('success', 'Review approved.');
    }

    public function reject($id)
    {
        $this->db->table('reviews')->where('id', $id)->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Review rejected.');
    }
}
