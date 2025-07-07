APP_CONTAINER_NAME = bonushelp-app

# Полный цикл: билд, ап, миграции
init-ci: down build up

# Команды Docker Compose
build:
	docker compose build --no-cache

up:
	docker compose up --pull always -d --wait

down:
	docker compose down --remove-orphans

# Doctrine команды
migrations-diff:
	docker compose exec php php bin/console doctrine:migrations:diff

migrations-migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrations-status:
	docker compose exec php php bin/console doctrine:migrations:status

migrations-rollback:
	docker compose exec php php bin/console doctrine:migrations:execute prev --down

migrations-make:
	docker compose exec php php bin/console make:migration

# generate jwt
jwt-generate:
	docker compose exec php php bin/console lexik:jwt:generate-keypair

# Запуск PHPStan анализа
static-analyse:
	docker compose exec php vendor/bin/phpstan --memory-limit=-1 --no-progress

# Исправление стиля
cs-fix:
	docker compose exec php vendor/bin/php-cs-fixer fix --cache-file=.php-cs-fixer.cache --show-progress=none --allow-risky=yes