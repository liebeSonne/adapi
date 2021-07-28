# Приложение

[< Назад](readme.md)

- [Структура](#Структура)
- [Ресурсы](#Ресурсы)
- [Роуты](#Роуты)
- [Примеры](#Примеры)

## Структура

### Структура кода:

 Раздел                     | Описание
----------------------------|-------
 /config/config.php         | подключаемые настройки
 /config/config.default.php | настройки по умолчанию
 /config/config.loсal.php   | возможные локальные настройки
 /scripts/install.php       | скрипт инсталяции (хранилище данных)
 /public/index.php          | точка входа/приема запросов
 /src/*                     | код приложения

### Основа

Запуск приложения осуществляется объектом класса App

```php
use App\App;

$app = new App($request, $response, $resources, $router);
$app->run();
```

Для работы, приложение использует объекты для разбора входных параметров, формирования результата, [Ресурсы](app.md#Ресурсы), [Роуты](#Роуты).

Интерфейсы этих объектов:
```php
use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;
use App\Common\Resources\ResourcesInterface;
use App\Common\Route\RouterInterface;

class App
{
    ...
    public function __construct(
        RequestInterface $request,
        ApiResponseInterface $response,
        ResourcesInterface $resources,
        RouterInterface $router
    ) {
...
```

Интерфейс для работы с запросами:

```php
namespace App\Common\Request;

interface RequestInterface
{
    // метод запроса - GET/POST
    public function getMethod(): string;
    // GET - параметры
    public function getQueryParams(): array;
    // POST-параметры
    public function getRequestParams(): array;
    // URL
    public function getUrl(): string;
}
```

Интерфейс для формирования ответов:

```php
namespace App\Common\Response;

interface ApiResponseInterface
{
    // установка всех параметров ответа
    public function set(int $code, string $message = '', array $data = []): ApiResponseInterface;
    // код ответа
    public function setCode(int $code): ApiResponseInterface;
    // сообщение ответа
    public function setMessage(string $message): ApiResponseInterface;
    // данные ответа
    public function setData(array $data): ApiResponseInterface;
    // запуск отображения сформированного результата
    public function display(): void;
}
```

## Ресурсы

Ресурсом является раздел/категоиря API.

Ресурсы реализуют интерфейс:

```php
namespace App\Common\Resources;

interface ResourceInterface
{
    // будет вызван роутом, подходящим под параметры запроса. 
    // $method - метод класса ресурса,
    // $args - аргументы из URL.
    public function call(string $method, array $args = []): void;
}
```

Например раздел API `/ads`:

```php 
namespace App\Api;

use App\Common\Resources\ResourceInterface;
...

// реализация ресурса рекламных объявлений
class AdsApi extends ApiBase implements ResourceInterface
{
    ...
    // POST /ads
    public function addAd(array $args = []): void
    {
    ...
    // POST /ads/123
    public function editAd(array $args = []): void
    {
        $id = $args['id'] ?? $args[0] ?? null;
    ...
    // GET /ads/relevant
    public function relevantAd(array $args = []): void
    {
    ...
```

Ресурсы регистрируются в специльном классе, привязкой названия класса к экземпляру объекта, реализующего интерфейс ресурса.


```php
namespace App\Common\Resources;

interface ResourcesInterface
{
    // регистрация ресурсов
    public function reg(string $class, ResourceInterface $resource): ResourcesInterface;
    // получение ресурса по классу, его реализующему
    public function getResource(string $class): ?ResourceInterface;
}

class Resources implements ResourcesInterface
...
```

Регистрация ресурса:

```php
use App\Common\Resources\Resources;
...

$resources = new Resources();
$resources->reg(AdsApi::class, $adsApi);

```

## Роуты

Назначение роутов в соединении типа и URL запроса с методами ресурсов, реализующих их обработку.

Роутер - осуществялет регистрацию и управление роутами.

```php
namespace App\Common\Route;

use App\Common\Request\RequestInterface;

interface RouterInterface
{
    // регистрация роута
    public function reg(RoutInterface $rout): RouterInterface;
    // поиск подходящго роута по параметрам запроса, которые получает от экземпляра объекта реализующего соответстующий интерфейс
    public function findRout(RequestInterface $request): ?RoutInterface;
}

class Router implements RouterInterface
...
```

Роут - осуществляет привязку к методу ресурса.

```php
namespace App\Common\Route;

use App\Common\Resources\ResourcesInterface;

interface RoutInterface
{
    // проверка метода запроса и URL на соотсетствие роуту
    public function isMatch(string $method, string $url): bool;
    // получение параметров запроса из URL
    public function getArguments(string $url): array;
    // вызов метода реурса образатывающего запрос роута
    public function callResource(ResourcesInterface $resources, string $url);
}

// реализация интерфейса роута
class Rout implements RoutInterface
{
    // $method - метод запроса
    // $pattern - регулярное выражение URL 
    // $class - имя класса ресурса
    // $action - имя метода ресурса, обрабатывающего запрос
    public function __construct(string $method, string $pattern, string $class, string $action)
...
```

Пример регистрации роутов рекламных объявлений:
```php
use App\Common\Route\Router;
use App\Common\Route\Rout;

$router = new Router();
$router->reg(new Rout('POST', '/^ads$/', AdsApi::class, 'addAd'));
$router->reg(new Rout('POST', '/^ads\/(?<id>\d+)$/', AdsApi::class, 'editAd'));
$router->reg(new Rout('GET', '/^ads\/relevant$/', AdsApi::class, 'relevantAd'));
```


## Примеры

В целом, для запуск приложения будет сделано следющее:

```php

use App\Common\Request\Request;
use App\Common\Response\JsonApiResponse;
use App\Common\Resources\Resources;
use App\Api\AdsApi;
use App\Common\Route\Router;
use App\Common\Route\Rout;
use App\App;

$request = new Request();
$response = new JsonApiResponse();

// Ресурсы - сами возвращают результат и получают данные запроса
$adsApi = new AdsApi($request, $response);

// Регистрация ресурсов
$resources = new Resources();
$resources->reg(AdsApi::class, $adsApi);

// Регистрация роутов
$router = new Router();
$router->reg(new Rout('POST', '/^ads$/', AdsApi::class, 'addAd'));
$router->reg(new Rout('POST', '/^ads\/(?<id>\d+)$/', AdsApi::class, 'editAd'));
$router->reg(new Rout('GET', '/^ads\/relevant$/', AdsApi::class, 'relevantAd'));

// App
$app = new App($request, $response, $resources, $router);
$app->run();
```
