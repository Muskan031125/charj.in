<?php
namespace App\Controllers\Tools;
use App\Controllers\BaseController;

class ResaleController extends BaseController {
    public function index() {
        $data = $this->globalData;
        $data['meta_title']       = 'EV Resale Value Estimator — What Will My EV Be Worth? | Charj.in';
        $data['meta_description'] = 'Estimate your EV resale value based on real depreciation data. Know what your electric vehicle will be worth before you buy.';
        return view('tools/resale_estimator', $data);
    }
}
