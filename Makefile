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

migration-migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrations-status:
	docker compose exec php php bin/console doctrine:migrations:status

migrations-rollback:
	docker compose exec php php bin/console doctrine:migrations:execute prev --down

migrations-make:
	docker compose exec php php bin/console make:migration
