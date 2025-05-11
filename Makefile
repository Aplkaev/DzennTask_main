# Makefile для управления Symfony-проектом через Docker Compose

PHP_CONTAINER = $(shell docker compose ps -q php)

# Команды Docker Compose
build:
	docker compose build --no-cache

up:
	docker compose up --pull always -d --wait

down:
	docker compose down --remove-orphans

# Doctrine команды
migration-diff:
	docker compose exec php php bin/console doctrine:migrations:diff

migration-migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migration-status:
	docker compose exec php php bin/console doctrine:migrations:status

migration-rollback:
	docker compose exec php php bin/console doctrine:migrations:execute prev --down

# Полный цикл: билд, ап, миграции
init: build up migration-diff migration-migrate