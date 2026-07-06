<?php
namespace App\Controllers\Tools;
use App\Controllers\BaseController;

class ChargingCostController extends BaseController {
    public function index() {
        $data = $this->globalData;
        $data['meta_title']       = 'EV Charging Cost Calculator — Cost Per KM | Charj.in';
        $data['meta_description'] = 'Calculate your real EV running cost per km. Enter your electricity tariff and EV efficiency to see exact cost.';
        return view('tools/charging_cost', $data);
    }
}
