<?php
foreach (glob('app/Database/Migrations/*.php') as $file) {
    $content = file_get_contents($file);
    $content = str_replace(", false, [", ", true, [", $content);
    file_put_contents($file, $content);
}
echo "Migrations fixed.\n";
