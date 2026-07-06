<?php
$isEdit = false;
$brand  = [];
?>
<?= view('admin/brands/form', ['isEdit' => false, 'brand' => [], 'errors' => $errors ?? []]) ?>
