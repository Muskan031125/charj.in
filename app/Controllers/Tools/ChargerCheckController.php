<?php
namespace App\Controllers\Tools;
use App\Controllers\BaseController;

class ChargerCheckController extends BaseController {
    public function index() {
        $data = $this->globalData;
        $data['meta_title']       = 'EV Charger Compatibility Checker — Does My EV Work Here? | Charj.in';
        $data['meta_description'] = 'Check which public EV chargers are compatible with your electric vehicle. Instant compatibility guide for all major Indian EVs.';
        return view('tools/charger_check', $data);
    }
}
