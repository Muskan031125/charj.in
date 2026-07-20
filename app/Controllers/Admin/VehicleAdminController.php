<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class VehicleAdminController extends AdminBaseController
{

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        return trim($slug, '-');
    }

    private function uniqueSlug(string $base, int $excludeId = 0): string
    {
        $slug      = $base;
        $counter   = 1;
        while (true) {
            $builder = $this->db->table('vehicles')->where('slug', $slug);
            if ($excludeId > 0) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                break;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    private function validateVehicle(array $post, int $excludeId = 0): array
    {
        $errors = [];

        $name = trim($post['name'] ?? '');
        if (strlen($name) < 3 || strlen($name) > 255) {
            $errors['name'] = 'Vehicle name must be between 3 and 255 characters.';
        }

        if (empty($post['brand_id']) || !ctype_digit((string) $post['brand_id'])) {
            $errors['brand_id'] = 'A valid brand is required.';
        }

        if (empty($post['category_id']) || !ctype_digit((string) $post['category_id'])) {
            $errors['category_id'] = 'A valid category is required.';
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }
        $slug = $this->uniqueSlug($slug, $excludeId);

        if (!is_numeric($post['starting_price'] ?? '')) {
            $errors['starting_price'] = 'Starting price must be a valid decimal number.';
        }

        if (!ctype_digit((string) ($post['claimed_range'] ?? ''))) {
            $errors['claimed_range'] = 'Claimed range must be a valid integer (km).';
        }

        $validStatuses = ['published', 'draft', 'discontinued'];
        if (!in_array($post['status'] ?? '', $validStatuses)) {
            $errors['status'] = 'Status must be published, draft, or discontinued.';
        }

        return [$errors, $slug];
    }

    // ---------------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------------

    public function index()
    {
        $search = $this->request->getGet('search');
        $page   = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $builder = $this->db->table('vehicles v')
            ->select('v.*, b.name AS brand_name, c.name AS category_name')
            ->join('brands b', 'b.id = v.brand_id', 'left')
            ->join('vehicle_categories c', 'c.id = v.category_id', 'left');

        if (!empty($search)) {
            $builder->like('v.name', $search);
        }

        $total    = $builder->countAllResults(false);
        $vehicles = $builder->orderBy('v.created_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        $totalPages = ceil($total / $perPage);

        return view('admin/vehicles/index', [
            'vehicles'   => $vehicles,
            'search'     => $search,
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
        ]);
    }

    public function create()
    {
        $brands     = $this->db->table('brands')->orderBy('name', 'ASC')->get()->getResultArray();
        $categories = $this->db->table('vehicle_categories')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/vehicles/create', [
            'brands'     => $brands,
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost();
        [$errors, $slug] = $this->validateVehicle($post);

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('vehicles')->insert([
            // Identity
            'brand_id'               => (int) $post['brand_id'],
            'category_id'            => (int) $post['category_id'],
            'name'                   => trim($post['name']),
            'slug'                   => $slug,
            'short_description'      => $post['short_description'] ?? null,
            'full_description'       => $post['full_description'] ?? null,
            'status'                 => $post['status'] ?? 'draft',
            'featured'               => !empty($post['featured']) ? 1 : 0,
            'segment'                => $post['segment'] ?? null,
            'body_type'              => $post['body_type'] ?? null,
            // Pricing
            'starting_price'         => !empty($post['starting_price']) ? (float) $post['starting_price'] : null,
            'max_price'              => !empty($post['max_price']) ? (float) $post['max_price'] : null,
            'ex_showroom_price'      => !empty($post['ex_showroom_price']) ? (float) $post['ex_showroom_price'] : null,
            // Battery & range
            'battery_capacity'       => !empty($post['battery_capacity']) ? (float) $post['battery_capacity'] : null,
            'battery_type'           => $post['battery_type'] ?? null,
            'real_world_range'       => !empty($post['real_world_range']) ? (int) $post['real_world_range'] : null,
            'claimed_range'          => !empty($post['claimed_range']) ? (int) $post['claimed_range'] : null,
            // Performance
            'top_speed_kmph'         => !empty($post['top_speed']) ? (int) $post['top_speed'] : null,
            'acceleration_0_60'      => !empty($post['acceleration_0_100']) ? (float) $post['acceleration_0_100'] : null,
            // Charging
            'charging_time_ac'       => $post['charging_time_normal'] ?? null,
            'charging_time_dc'       => $post['charging_time_fast'] ?? null,
            'fast_charging_supported'=> !empty($post['fast_charging_supported']) ? 1 : 0,
            'charging_connector_type'=> $post['fast_charging_type'] ?? null,
            // Motor
            'motor_power_kw'         => !empty($post['motor_power']) ? (float) $post['motor_power'] : null,
            'torque_nm'              => !empty($post['motor_torque']) ? (float) $post['motor_torque'] : null,
            // Warranty
            'warranty_years'         => !empty($post['warranty_years']) ? (int) $post['warranty_years'] : null,
            'warranty_km'            => !empty($post['warranty_km']) ? (int) $post['warranty_km'] : null,
            'battery_warranty_years' => !empty($post['battery_warranty_years']) ? (int) $post['battery_warranty_years'] : null,
            'battery_warranty_km'    => !empty($post['battery_warranty_km']) ? (int) $post['battery_warranty_km'] : null,
            // Physical
            'seating_capacity'       => !empty($post['seating_capacity']) ? (int) $post['seating_capacity'] : null,
            'weight_kg'              => !empty($post['kerb_weight']) ? (int) $post['kerb_weight'] : null,
            'ground_clearance_mm'    => !empty($post['ground_clearance']) ? (int) $post['ground_clearance'] : null,
            'boot_space_litres'      => !empty($post['boot_space']) ? (int) $post['boot_space'] : null,
            // Media — prefer uploaded file over URL field
            'image_url'              => $this->resolveImageUrl($post['image_url'] ?? null),
            'video_url'              => $post['video_url'] ?? null,
            // JSON fields
            'features_json'          => $post['features_json'] ?? null,
            'pros_json'              => $post['pros_json'] ?? null,
            'cons_json'              => $post['cons_json'] ?? null,
            'colors_json'            => $post['colors_json'] ?? null,
            // SEO
            'meta_title'             => $post['meta_title'] ?? null,
            'meta_description'       => $post['meta_description'] ?? null,
            // Ratings
            'expert_rating'          => !empty($post['expert_rating']) ? (float) $post['expert_rating'] : null,
            'best_for'               => $post['best_for'] ?? null,
            'expert_review'          => $post['expert_review'] ?? null,
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/vehicles')->with('success', 'Vehicle created successfully.');
    }

    public function edit(int $id)
    {
        $vehicle = $this->db->table('vehicles')->where('id', $id)->get()->getRowArray();
        if (!$vehicle) {
            return redirect()->to('/admin/vehicles')->with('error', 'Vehicle not found.');
        }

        $brands     = $this->db->table('brands')->orderBy('name', 'ASC')->get()->getResultArray();
        $categories = $this->db->table('vehicle_categories')->orderBy('name', 'ASC')->get()->getResultArray();
        $galleryImages = $this->db->table('vehicle_images')
            ->where('vehicle_id', $id)
            ->whereIn('image_type', ['gallery', 'interior'])
            ->orderBy('image_type', 'ASC')
            ->orderBy('display_order', 'ASC')
            ->get()->getResultArray();

        return view('admin/vehicles/edit', [
            'vehicle'       => $vehicle,
            'brands'        => $brands,
            'categories'    => $categories,
            'galleryImages' => $galleryImages,
        ]);
    }

    /** Upload a gallery or interior photo for a vehicle — adds a new vehicle_images row. */
    public function uploadImage(int $id)
    {
        $vehicle = $this->db->table('vehicles')->where('id', $id)->get()->getRowArray();
        if (!$vehicle) {
            return redirect()->to('/admin/vehicles')->with('error', 'Vehicle not found.');
        }

        $imageType = $this->request->getPost('image_type') === 'interior' ? 'interior' : 'gallery';
        $file      = $this->request->getFile('image_file');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/admin/vehicles/edit/' . $id)->with('error', 'Please choose an image file to upload.');
        }

        $dir = FCPATH . 'assets/images/vehicles/' . $vehicle['slug'] . '/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ext  = $file->getClientExtension();
        $name = url_title(pathinfo($file->getClientName(), PATHINFO_FILENAME), '-', true) . '-' . time() . '.' . $ext;
        $file->move($dir, $name);
        $url = base_url('assets/images/vehicles/' . $vehicle['slug'] . '/' . $file->getName());

        $maxOrder = $this->db->table('vehicle_images')
            ->selectMax('display_order')
            ->where('vehicle_id', $id)
            ->where('image_type', $imageType)
            ->get()->getRowArray();

        $this->db->table('vehicle_images')->insert([
            'vehicle_id'    => $id,
            'image_type'    => $imageType,
            'image_url'     => $url,
            'display_order' => (int) ($maxOrder['display_order'] ?? 0) + 1,
        ]);

        return redirect()->to('/admin/vehicles/edit/' . $id)->with('success', ucfirst($imageType) . ' photo uploaded.');
    }

    /** Remove a single gallery/interior photo row. */
    public function deleteImage(int $vehicleId, int $imageId)
    {
        $this->db->table('vehicle_images')
            ->where('id', $imageId)
            ->where('vehicle_id', $vehicleId)
            ->delete();

        return redirect()->to('/admin/vehicles/edit/' . $vehicleId)->with('success', 'Photo removed.');
    }

    public function update(int $id)
    {
        $vehicle = $this->db->table('vehicles')->where('id', $id)->get()->getRowArray();
        if (!$vehicle) {
            return redirect()->to('/admin/vehicles')->with('error', 'Vehicle not found.');
        }

        $post = $this->request->getPost();
        [$errors, $slug] = $this->validateVehicle($post, $id);

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('vehicles')->where('id', $id)->update([
            // Identity
            'brand_id'               => (int) $post['brand_id'],
            'category_id'            => (int) $post['category_id'],
            'name'                   => trim($post['name']),
            'slug'                   => $slug,
            'short_description'      => $post['short_description'] ?? null,
            'full_description'       => $post['full_description'] ?? null,
            'status'                 => $post['status'] ?? 'draft',
            'featured'               => !empty($post['featured']) ? 1 : 0,
            'segment'                => $post['segment'] ?? null,
            'body_type'              => $post['body_type'] ?? null,
            // Pricing
            'starting_price'         => !empty($post['starting_price']) ? (float) $post['starting_price'] : null,
            'max_price'              => !empty($post['max_price']) ? (float) $post['max_price'] : null,
            'ex_showroom_price'      => !empty($post['ex_showroom_price']) ? (float) $post['ex_showroom_price'] : null,
            // Battery & range
            'battery_capacity'       => !empty($post['battery_capacity']) ? (float) $post['battery_capacity'] : null,
            'battery_type'           => $post['battery_type'] ?? null,
            'real_world_range'       => !empty($post['real_world_range']) ? (int) $post['real_world_range'] : null,
            'claimed_range'          => !empty($post['claimed_range']) ? (int) $post['claimed_range'] : null,
            // Performance
            'top_speed_kmph'         => !empty($post['top_speed']) ? (int) $post['top_speed'] : null,
            'acceleration_0_60'      => !empty($post['acceleration_0_100']) ? (float) $post['acceleration_0_100'] : null,
            // Charging
            'charging_time_ac'       => $post['charging_time_normal'] ?? null,
            'charging_time_dc'       => $post['charging_time_fast'] ?? null,
            'fast_charging_supported'=> !empty($post['fast_charging_supported']) ? 1 : 0,
            'charging_connector_type'=> $post['fast_charging_type'] ?? null,
            // Motor
            'motor_power_kw'         => !empty($post['motor_power']) ? (float) $post['motor_power'] : null,
            'torque_nm'              => !empty($post['motor_torque']) ? (float) $post['motor_torque'] : null,
            // Warranty
            'warranty_years'         => !empty($post['warranty_years']) ? (int) $post['warranty_years'] : null,
            'warranty_km'            => !empty($post['warranty_km']) ? (int) $post['warranty_km'] : null,
            'battery_warranty_years' => !empty($post['battery_warranty_years']) ? (int) $post['battery_warranty_years'] : null,
            'battery_warranty_km'    => !empty($post['battery_warranty_km']) ? (int) $post['battery_warranty_km'] : null,
            // Physical
            'seating_capacity'       => !empty($post['seating_capacity']) ? (int) $post['seating_capacity'] : null,
            'weight_kg'              => !empty($post['kerb_weight']) ? (int) $post['kerb_weight'] : null,
            'ground_clearance_mm'    => !empty($post['ground_clearance']) ? (int) $post['ground_clearance'] : null,
            'boot_space_litres'      => !empty($post['boot_space']) ? (int) $post['boot_space'] : null,
            // Media — prefer uploaded file over URL field
            'image_url'              => $this->resolveImageUrl($post['image_url'] ?? null),
            'video_url'              => $post['video_url'] ?? null,
            // JSON fields
            'features_json'          => $post['features_json'] ?? null,
            'pros_json'              => $post['pros_json'] ?? null,
            'cons_json'              => $post['cons_json'] ?? null,
            'colors_json'            => $post['colors_json'] ?? null,
            // SEO
            'meta_title'             => $post['meta_title'] ?? null,
            'meta_description'       => $post['meta_description'] ?? null,
            // Ratings
            'expert_rating'          => !empty($post['expert_rating']) ? (float) $post['expert_rating'] : null,
            'best_for'               => $post['best_for'] ?? null,
            'expert_review'          => $post['expert_review'] ?? null,
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/vehicles')->with('success', 'Vehicle updated successfully.');
    }

    /** Upload vehicle image file, return public URL (or fall back to provided URL string). */
    private function resolveImageUrl(?string $urlField): ?string
    {
        $file = $this->request->getFile('image_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . 'assets/images/vehicles/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext  = $file->getClientExtension();
            $name = url_title(pathinfo($file->getClientName(), PATHINFO_FILENAME), '-', true) . '-' . time() . '.' . $ext;
            $file->move($dir, $name);
            return base_url('assets/images/vehicles/' . $file->getName());
        }
        return $urlField ?: null;
    }

    public function bulkImport()
    {
        return view('admin/vehicles/bulk_import');
    }

    public function bulkImportProcess()
    {
        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please upload a valid CSV file.');
        }

        $content = file_get_contents($file->getTempName());
        // Normalize line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $lines   = explode("\n", $content);
        $headers = str_getcsv(array_shift($lines));

        $imported = 0;
        $errors   = [];
        $model    = new \App\Models\VehicleModel();

        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            $row = str_getcsv($line);
            if (count($row) !== count($headers)) {
                $errors[] = "Row " . ($i + 1) . ": column count mismatch (expected " . count($headers) . ", got " . count($row) . ")";
                continue;
            }
            $data = array_combine($headers, $row);

            // Resolve brand_id from brand name
            if (!empty($data['brand_name'])) {
                $brand = \Config\Database::connect()->table('brands')->where('name', $data['brand_name'])->get()->getRowArray();
                $data['brand_id'] = $brand['id'] ?? null;
                unset($data['brand_name']);
            }

            // Auto-slug
            if (!empty($data['name']) && empty($data['slug'])) {
                $data['slug'] = url_title($data['name'], '-', true);
            }

            // Ensure unique slug
            if (!empty($data['slug'])) {
                $data['slug'] = $this->uniqueSlug($data['slug']);
            }

            try {
                $existing = !empty($data['slug']) ? $model->where('slug', $data['slug'])->first() : null;
                if ($existing) {
                    $model->update($existing['id'], $data);
                } else {
                    $model->insert($data);
                }
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        $msg = "Imported {$imported} vehicle(s).";
        if (!empty($errors)) {
            $msg .= ' Errors: ' . implode('; ', $errors);
        }

        return redirect()->to('/admin/vehicles')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $vehicle = $this->db->table('vehicles')->where('id', $id)->get()->getRowArray();
        if (!$vehicle) {
            return redirect()->to('/admin/vehicles')->with('error', 'Vehicle not found.');
        }

        // Soft delete: set status to discontinued
        $this->db->table('vehicles')->where('id', $id)->update([
            'status'     => 'discontinued',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/vehicles')->with('success', 'Vehicle marked as discontinued.');
    }
}
