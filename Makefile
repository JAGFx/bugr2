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
	@bin/php bin/console d:d:d -f --if-exists
	@bin/php bin/console d:d:c
	@bin/php bin/console d:m:m -n

install: start db
	@bin/php composer install
	@npm i

fixture:
	@bin/php bin/console h:f:l -n --purge-with-truncate
# -- End Environment

# -- Start Code linter & test (CI)
test:
#	@bin/php bin/console d:d:d -f --if-exists --env=test
#	@bin/php bin/console d:d:c --env=test
#	@bin/php bin/console d:m:m -n --env=test
#	@bin/php bin/console h:f:l -n --purge-with-truncate --env=test
	@bin/php bin/phpunit

lint:
	@bin/php php-cs-fixer fix --using-cache=no --diff
	@bin/php ./vendor/bin/psalm
	@bin/php vendor/bin/phpcs -v --standard=.phpcs.xml -s --no-cache --colors src
	@bin/php vendor/bin/phpcpd src
	@npm run lint

ci: lint test
# -- End Code linter & test (CI)
