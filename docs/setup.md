
# Установка и настройка

[< Назад](readme.md)

- [Установка](#Установка)
- [Настройка](#Настройка)
- [Примеры](#Примеры)


## Установка

### Установка зависимостей

```bash
$ composer install
```

### Инсталяция данных

```bash
$ php ./scripts/install.php
```

или

```bash
$ composer build
```

### Настройка хоста

Все запросы поступают на `./public/index.php`.

Пример настройки переадресации для сервера apache2:

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA, L]
```


### Запуск на PHP-сервере:

```bash
$ php -S 127.0.0.1:8080 -t public
```

или

```bash
$ composer php-server
```

## Настройка

Настройки приложения

`./config/config.default.php` - настройки по умолчанию

`./config/config.local.php` - добавить файл локальных настроек вместо настроек по умочланию

### Пример настроек

файл `./config/config.default.php`

```php
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
```

 Параметр | Описание
----------|---------------
 mode     | = *prod*/*dev* Режим работы приложения. В *dev* режиме отображаются ошибки и исключения.  
db        | Раздел настроек относящихся к базе данных
db.filename | строка настройки базы данных, например путь к файлу базы данных
db.schema | Запросы для инициализации структур базы данных
relevant | Раздел настроек показа рекламных объявлений
relevant.strategy | Указание названия стратегии для выбора отображаемого рекламного объявления


## Примеры

Запуск установка и локального сервера:

```bash
$ composer install
$ composer build
$ composer php-server
```

дальше отправка запросов

```bash
curl -i -X POST -H 'Content-Type: application/x-www-form-urlencoded' http://127.0.0.1:8080/ads -d "text=Advertisement1&price=300&limit=1000&banner=https://linktoimage.png"

curl -i -X 'GET' -H 'accept: application/json' http://127.0.0.1:8080/ads/relevant 

curl -i -X POST -H 'Content-Type: application/x-www-form-urlencoded' http://127.0.0.1:8080/ads/1 -d "text=Advertisement1&price=450&limit=1200&banner=https://linktoimage.png"
```
