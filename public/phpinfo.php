<?php
// Script de diagnostic pour vérifier les limites PHP
echo "<h2>Configuration PHP pour les uploads de fichiers</h2>";
echo "<table border='1' style='border-collapse: collapse; margin: 20px;'>";
echo "<tr><th style='padding: 10px;'>Paramètre</th><th style='padding: 10px;'>Valeur actuelle</th><th style='padding: 10px;'>Recommandé</th></tr>";

$configs = [
    'upload_max_filesize' => '100M',
    'post_max_size' => '100M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '256M',
    'max_file_uploads' => '20'
];

foreach ($configs as $key => $recommended) {
    $current = ini_get($key);
    $color = ($current == $recommended) ? 'green' : 'red';
    echo "<tr>";
    echo "<td style='padding: 10px;'>{$key}</td>";
    echo "<td style='padding: 10px; color: {$color};'>{$current}</td>";
    echo "<td style='padding: 10px;'>{$recommended}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<p><strong>Note:</strong> Si les valeurs actuelles ne correspondent pas aux recommandations, contactez votre administrateur système.</p>";

// Afficher aussi la taille maximale calculée pour les uploads
$upload_mb = (int) ini_get('upload_max_filesize');
$post_mb = (int) ini_get('post_max_size');
$max_upload = min($upload_mb, $post_mb);
echo "<p><strong>Taille maximale effective pour les uploads:</strong> {$max_upload}MB</p>";
?>