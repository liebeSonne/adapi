<?php

if (file_exists(__DIR__ . '/config.local.php')) {
    return include __DIR__ . '/config.local.php';
}

return include __DIR__ . '/config.default.php';
