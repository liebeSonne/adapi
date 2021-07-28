<?php

return [
    'mode' => 'dev', // prod/dev
    'db' => [
        'filename' => __DIR__ . '/../ads.db.sqlite',
        'schema' => [
            'ads' => '
                CREATE TABLE IF NOT EXISTS `ads` (
                    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                    `text` TEXT DEFAULT "",
                    `price` INTEGER DEFAULT 0,
                    `limit` INTEGER DEFAULT 0,
                    `banner` TEXT DEFAULT "",
                    `countShows` INTEGER DEFAULT 0,
                    `timeLastShow` INTEGER DEFAULT 0
                );',
        ],
    ],
    'relevant' => [
        'strategy' => 'advanced', // default|advanced|null
    ],
];
