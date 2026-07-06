<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\CategoryModel;
use App\Models\BrandModel;

abstract class BaseController extends Controller
{
    /** @var CLIRequest|IncomingRequest */
    protected $request;
    // Note: $helpers type must NOT be declared here — CI4 Controller defines it without type
    protected $helpers = ['form', 'url', 'text', 'charj'];
    protected array $globalData = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load global data needed in all views
        $this->globalData = [
            'categories'  => (new CategoryModel())->getTopLevel(),
            'gaId'        => getenv('GA_MEASUREMENT_ID'),
            'metaPixelId' => getenv('META_PIXEL_ID'),
        ];
    }

    protected function render(string $view, array $data = []): string
    {
        $data = array_merge($this->globalData, $data);

        $data['meta_title']       = $data['meta_title']       ?? 'Charj.in - India\'s EV Decision Engine';
        $data['meta_description'] = $data['meta_description'] ?? 'Find, compare and choose the right electric vehicle in India. Compare EVs, calculate savings, find dealers and charging stations.';
        $data['meta_keywords']    = $data['meta_keywords']    ?? 'electric vehicles India, EV comparison, buy EV, electric scooter, electric car, EV price';

        return view($view, $data);
    }

    protected function jsonResponse(array $data, int $status = 200)
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }
}
