<?php

// Execute Laravel artisan commands
$output = [];

// Clear config cache
exec('php artisan config:clear', $output);
echo '<pre>Config Clear Output: ' . print_r($output, true) . '</pre>';

// Clear cache
exec('php artisan cache:clear', $output);
echo '<pre>Cache Clear Output: ' . print_r($output, true) . '</pre>';

//clear route
exec('php artisan route:clear',$output);
echo '<pre>Route Clear Output :' . print_r($output.true) . '</pre>';

// clear vew
exec('php artisan view:clear',$output);
echo '<pre>View  Clear Output :' . print_r($output.true) . '</pre>';


?>
