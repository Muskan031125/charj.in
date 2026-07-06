<?= view('admin/vehicles/form', [
    'brands'     => $brands     ?? [],
    'categories' => $categories ?? [],
    'vehicle'    => $vehicle    ?? [],
    'errors'     => $errors     ?? [],
]) ?>
