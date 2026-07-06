<?= view('admin/vehicles/form', [
    'brands'     => $brands     ?? [],
    'categories' => $categories ?? [],
    'vehicle'    => [],
    'errors'     => $errors     ?? [],
]) ?>
