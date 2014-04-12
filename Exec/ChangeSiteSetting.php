<?php
// And our libraries
include 'Essential.php';

function strip($in) {
   return preg_replace('/\r?\n$/', '', $in);  
}

echo "Setting name: ";
$name = strip(fgets(STDIN));

echo "Value: ";
$value = strip(fgets(STDIN));

SettingsLib::set($name, $value);

$setting = SettingsLib::get($name);
echo "$name is now $setting\n\n";
