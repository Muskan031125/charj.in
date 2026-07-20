<?php

namespace App\Controllers\Tools;

use App\Controllers\BaseController;

class BatteryCostController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title'       => 'EV Battery Replacement Cost Estimator | Charj.in',
            'description' => 'Find out exactly how much it will cost to replace your EV battery, warranty coverage details and future price projections.',
        ];
        return view('tools/battery_cost', $data);
    }
}
