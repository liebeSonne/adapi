
# API рекламных объявлений

## Установка

### Установка зависимостей

```
$ composer install
```

### Инсталяция данных 

```
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

### Запуск на PHP-сервере

```
$ php -S 127.0.0.1:8080 -t public
```

## Настройки

`./config/config.default.php` - настройки по умолчанию

`./config/config.local.php` - добавить файл локальных настроек вместо настроек по умочланию

## Документация

Полная документация [здесь](./docs/readme.md).

[Установка и настройка](./docs/setup.md).

[Описание приложения](./docs/app.md).

[Протокол API](./docs/protocol.md).

[Тесты](./docs/tests.md).

## Тестирование

Для тестирования приложения.
Указать путь к api `api_base_url` в `./phpunit.xml`, для phpunit тестов.

```
<env name="api_base_url" value="http://127.0.0.1:8080"/>
```

Запустить тесты

```
$ composer test
```