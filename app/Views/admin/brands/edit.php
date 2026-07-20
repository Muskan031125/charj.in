<?php
// $brand is passed by BrandAdminController::edit()
?>
<?= view('admin/brands/form', ['isEdit' => true, 'brand' => $brand ?? [], 'errors' => $errors ?? []]) ?>
