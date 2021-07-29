
# API Протокол

[< Назад](readme.md)

- [Рекламные объявления](#Рекламные-объявления)
    - [Структура данных](#Структура-данных)
    - [Формат ответа](#Формат-ответа)
    - [Запросы](#Запросы)
        - [Добавление](#Добавление)
        - [Изменение](#Изменение)
        - [Отображение](#Отображение)
    - [Коды и сообщения](#Коды-и-сообщения)

## Рекламные объявления

Запросы в раздел `/ads`

### Структура данных

#### Рекламное объявление:

 Параметр | Тип    | Описание
----------|--------|----------------------------
 id       | int    | идентификатор объявления
 text     | string | Заголовок/текст объявления 
 price    | int    | Стоимость одного показа
 limit    | int    | Лимит показа
 banner   | string | Ссылка на изображение баннера `jpg|jpeg|png`

### Формат ответа

Формат ответа в  JSON.
Статус кода ответа на запрос: `200`, для успешных ответов от сервера. 

#### Пример успешного ответа

```
HTTP/1.1 200 OK
Content-Type: application/json
...
{
  "message": "OK",
  "code": 200,
  "data": {
     "id": 123,
     "text": "Advertisement1",
     "banner": "https://linktoimage.png"   
  }
}
```

### Пример ответа с ошибкой валидации поля

```http request
HTTP/1.1 200 OK
Content-Type: application/json
...
{
  "message": "Invalid banner link",
  "code": 400,
  "data": {}
}
```

#### Описание 

 Поле    | Описание
---------|---------
 message | Сообщение ответа, может содержать текст ошибки
 code    | Код ответа, может содержать код ошибки
 data    | Данные рекламного объявления

### Добавление

Добавление рекламного объявления.

`POST`-запрос на адрес `/ads` c параметрами: `text`, `price`, `limit`, `banner`. 

Возвращает данные рекламного объявления: `id`, `text`, `banner`.

#### Пример

```http request
POST /ads HTTP/1.1

Content-Type: application/x-www-form-urlencoded
...

text=Advertisement1&price=300&limit=1000&banner=https://linktoimage.png
```

### Изменение

Изменение рекламного объявления.

`POST`-запрос на адрес `/ads/:id`, где `:id` - идентификатор рекламного объявления, c параметрами: `text`, `price`, `limit`, `banner`.

Возвращает данные рекламного объявления: `id`, `text`, `banner`.

#### Пример

```http request
POST /ads/123 HTTP/1.1

Content-Type: application/x-www-form-urlencoded
...

text=Advertisement123&price=450&limit=1200&banner=https://linktoimage.png
```

### Отображение

Выборка объявления для отображения/открутки.

Объявление может вернуться не больее `limit` раз.

`GET`-запрос на адрес `/ads/relevant`.

Возвращает данные рекламного объявления: `id`, `text`, `banner`.

#### Пример

```http request
GET /ads/relevant HTTP/1.1

...
```

### Коды и сообщения

code | message
-----|------------------------
 200 | Ok
 404 | Invalid rout
 404 | Invalid resource
 405 | Invalid resource method
 400 | Invalid argument
 400 | Invalid text
 400 | Invalid banner link
 400 | Invalid Invalid limit
 400 | Invalid price
 404 | Can not show
 500 | Can not add
 500 | Can not edit
 500 | Server Error
