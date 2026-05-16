<?php
$content = file_get_contents('addressData.ts');
// Find where the object starts
$startPos = strpos($content, 'export const PH_ADDRESS_DATA');
if ($startPos !== false) {
    $content = substr($content, $startPos);
}
// Convert export const to const
$content = str_replace('export const PH_ADDRESS_DATA: Record<string, ProvinceInfo | {}> =', 'const PH_ADDRESS_DATA =', $content);
// Ensure it's closed
if (substr(trim($content), -2) !== '};') {
    $content = rtrim(trim($content), ',') . "\n};\n";
}
file_put_contents('addressData.js', $content);
echo "addressData.js created successfully.\n";
?>