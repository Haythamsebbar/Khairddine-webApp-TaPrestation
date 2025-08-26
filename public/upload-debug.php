<?php
// Debug script to check upload limits from web server context
echo "<h2>PHP Upload Configuration Debug</h2>";
echo "<table border='1' style='border-collapse: collapse; margin: 20px;'>";
echo "<tr><th style='padding: 10px;'>Setting</th><th style='padding: 10px;'>Value</th></tr>";

$settings = [
    'upload_max_filesize',
    'post_max_size', 
    'memory_limit',
    'max_execution_time',
    'max_input_time',
    'max_file_uploads',
    'max_input_vars'
];

foreach ($settings as $setting) {
    $value = ini_get($setting);
    echo "<tr><td style='padding: 10px;'>{$setting}</td><td style='padding: 10px;'>{$value}</td></tr>";
}

echo "</table>";

// Convert sizes to bytes for comparison
function convertToBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int) $val;
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

$uploadMaxBytes = convertToBytes(ini_get('upload_max_filesize'));
$postMaxBytes = convertToBytes(ini_get('post_max_size'));

echo "<h3>Calculated Limits</h3>";
echo "<p>Upload max: " . number_format($uploadMaxBytes) . " bytes (" . round($uploadMaxBytes/1024/1024, 2) . " MB)</p>";
echo "<p>POST max: " . number_format($postMaxBytes) . " bytes (" . round($postMaxBytes/1024/1024, 2) . " MB)</p>";

echo "<h3>Effective Upload Limit</h3>";
$effectiveLimit = min($uploadMaxBytes, $postMaxBytes);
echo "<p>Effective limit: " . number_format($effectiveLimit) . " bytes (" . round($effectiveLimit/1024/1024, 2) . " MB)</p>";

// Check if we're in development server
echo "<h3>Server Information</h3>";
echo "<p>SAPI: " . php_sapi_name() . "</p>";
echo "<p>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
?>