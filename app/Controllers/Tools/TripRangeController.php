<?php
namespace App\Controllers\Tools;
use App\Controllers\BaseController;

class TripRangeController extends BaseController {
    public function index() {
        $data = $this->globalData;
        $data['meta_title']       = 'EV Trip Range Checker — Can My EV Make It? | Charj.in';
        $data['meta_description'] = 'Check if your EV can complete a trip without stopping to charge. Enter route distance and your EV range to find out instantly.';
        return view('tools/trip_range', $data);
    }
}
