# Dzentask

Dzentask — это минималистичный таск-менеджер, ориентированный на фокусировку, спокойствие и приоритет. Без перегрузки интерфейса, с поддержкой микросервисной архитектуры и real-time уведомлений через Centrifugo.

## Getting Started

```bash
make build     # Собрать контейнеры без кэша
make up        # Поднять контейнеры и дождаться их готовности
make down      # Остановить и удалить контейнеры
make migration-diff     # Сгенерировать новую миграцию на основе изменений в Entity
make migration-migrate  # Выполнить все миграции
make migration-status   # Посмотреть текущий статус миграций
make migration-rollback # Откат последней миграции
make init
```

## Features
![Schema](./Dzentask.png)

* Production, development and CI ready
* Just 1 service by default
* Blazing-fast performance thanks to [the worker mode of FrankenPHP](https://github.com/dunglas/frankenphp/blob/main/docs/worker.md) (automatically enabled in prod mode)
* [Installation of extra Docker Compose services](docs/extra-services.md) with Symfony Flex
* Automatic HTTPS (in dev and prod)
* HTTP/3 and [Early Hints](https://symfony.com/blog/new-in-symfony-6-3-early-hints) support
* Real-time messaging thanks to a built-in [Mercure hub](https://symfony.com/doc/current/mercure.html)
* [Vulcain](https://vulcain.rocks) support
* Native [XDebug](docs/xdebug.md) integration
* Super-readable configuration

**Enjoy!**

## Docs

1. [Options available](docs/options.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Debugging with Xdebug](docs/xdebug.md)
6. [TLS Certificates](docs/tls.md)
7. [Using MySQL instead of PostgreSQL](docs/mysql.md)
8. [Using Alpine Linux instead of Debian](docs/alpine.md)
9. [Using a Makefile](docs/makefile.md)
10. [Updating the template](docs/updating.md)
11. [Troubleshooting](docs/troubleshooting.md)

## License

Symfony Docker is available under the MIT License.
