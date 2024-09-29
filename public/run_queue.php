<?php
// Path to your Laravel application
$output = [];
exec('php artisan queue:work', $output);
echo '<pre>' . print_r($output, true) . '</pre>';
?>
