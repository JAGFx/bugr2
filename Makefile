# -- Start Docker
start:
	@docker compose up -d

stop:
	@docker compose down
# -- End Docker

# -- Start Environment
build: stop
	@docker compose build --pull --no-cache

db: start
	@sleep 1s
	@bin/php bin/console d:d:d -f --if-exists
	@bin/php bin/console d:d:c
	@bin/php bin/console d:m:m -n

db\:test: start
	@sleep 1s
	@bin/php bin/console d:d:d -f --if-exists --env=test
	@bin/php bin/console d:d:c --env=test
	@bin/php bin/console d:m:m -n --env=test
	@bin/php bin/console h:f:l -n --purge-with-truncate --env=test

install: start db
	@sleep 1s
	@bin/php composer install
	@npm i

fixture:
	@bin/php bin/console h:f:l -n --purge-with-truncate
# -- End Environment

# -- Start Code linter & test (CI)
test: db\:test test\:unit test\:functional
	@bin/php bin/phpunit

test\:unit:
	@bin/php bin/phpunit tests/Unit

test\:functional:
	@bin/php bin/phpunit tests/Functionnal

lint:
	@bin/php php-cs-fixer fix --using-cache=no --diff
	@bin/php ./vendor/bin/psalm
	@bin/php vendor/bin/phpcs -v --standard=.phpcs.xml -s --no-cache --colors src
	@bin/php vendor/bin/phpcpd src
	@npm run lint

ci: lint test
# -- End Code linter & test (CI)
