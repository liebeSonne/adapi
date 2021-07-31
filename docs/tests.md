# Тесты

[< Назад](readme.md)

- [Консольные команды](#Консольные-команды)

## Консольные команды

Сформировать html с отчетом покрытия тестами в `./output/code-coverage/html/`:

```
composer test:coverage-html
```

Запуск тестов статических анализаторов:

```
composer test:stat
```

Запуск phpunit тестов:

```
composer phpunit
```

Запуск тестов статических анализаторов и phpunit тестов:

```
composer test
```

Запуск phpunit тестов классов приложения:

```
composer test:unit
```

Запуск phpunit тестов протокола приложения на хосте, указанном в `api_base_url` файла `./phpunit.xml`:

```
composer test:feature
```