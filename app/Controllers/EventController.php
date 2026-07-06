<?php

namespace App\Controllers;

use App\Models\EventModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EventController extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();
        $now = date('Y-m-d H:i:s');

        // Get upcoming events first, then past events
        $upcomingEvents = $eventModel
            ->where('status', 'published')
            ->where('start_date >=', $now)
            ->orderBy('start_date', 'ASC')
            ->paginate(12, 'default');

        // Featured events for sidebar/banner
        $featuredEvents = $eventModel
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderBy('start_date', 'ASC')
            ->limit(3)
            ->findAll();

        return $this->render('events/index', [
            'events'          => $upcomingEvents,
            'pager'           => $eventModel->pager,
            'featuredEvents'  => $featuredEvents,
            'meta_title'      => 'EV Events, Expos & Launches in India | Charj.in',
            'meta_description' => 'Discover upcoming electric vehicle expos, launches, test drives, and webinars. Register for events near you.',
        ]);
    }

    public function show(string $slug)
    {
        $eventModel = new EventModel();
        $event = $eventModel->where('slug', $slug)->where('status', 'published')->first();

        if (!$event) {
            throw PageNotFoundException::forPageNotFound('Event not found');
        }

        // Related events (upcoming, exclude current)
        $relatedEvents = [];
        $now = date('Y-m-d H:i:s');
        $relatedEvents = $eventModel
            ->where('status', 'published')
            ->where('start_date >=', $now)
            ->where('id !=', $event['id'])
            ->orderBy('start_date', 'ASC')
            ->limit(3)
            ->findAll();

        $metaTitle = $event['title'] . ' | Charj.in Events';
        $metaDesc  = substr(strip_tags($event['description'] ?? ''), 0, 155);

        return $this->render('events/show', [
            'event'          => $event,
            'relatedEvents'  => $relatedEvents,
            'meta_title'     => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
