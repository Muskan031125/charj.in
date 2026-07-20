<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AnnouncementController extends BaseController
{
    public function index()
    {
        $announcementModel = new AnnouncementModel();

        $announcements = $announcementModel
            ->where('status', 'published')
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->paginate(12, 'default');

        // Pinned announcements for sidebar
        $pinnedAnnouncements = $announcementModel
            ->where('status', 'published')
            ->where('is_pinned', 1)
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();

        return $this->render('announcements/index', [
            'announcements'     => $announcements,
            'pager'            => $announcementModel->pager,
            'pinnedAnnouncements' => $pinnedAnnouncements,
            'meta_title'       => 'EV News & Announcements | Charj.in',
            'meta_description' => 'Stay updated with the latest EV news, subsidies, policy changes, and product announcements.',
        ]);
    }

    public function show(string $slug)
    {
        $announcementModel = new AnnouncementModel();
        $announcement = $announcementModel->where('slug', $slug)->where('status', 'published')->first();

        if (!$announcement) {
            throw PageNotFoundException::forPageNotFound('Announcement not found');
        }

        // Related announcements (same type or just latest)
        $relatedAnnouncements = [];
        $relatedAnnouncements = $announcementModel
            ->where('status', 'published')
            ->where('id !=', $announcement['id'])
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();

        $metaTitle = $announcement['title'] . ' | Charj.in';
        $metaDesc  = substr(strip_tags($announcement['content'] ?? ''), 0, 155);

        return $this->render('announcements/show', [
            'announcement'     => $announcement,
            'relatedAnnouncements' => $relatedAnnouncements,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
