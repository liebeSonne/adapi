<?php

$localFile = __DIR__ . '/config.local.php';

if (file_exists($localFile)) {
    return include $localFile;
}

return include __DIR__ . '/config.default.php';
