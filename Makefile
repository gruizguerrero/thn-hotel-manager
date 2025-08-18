SHELL=/bin/bash

PLATFORM := $(shell uname -s)

.PHONY: php
php:
	docker compose exec php-fpm /bin/bash

.PHONY: test-unit
test-unit:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=coverage bin/phpunit -c tests/Unit/phpunit.xml --testdox ${parameters}"

.PHONY: test-acceptance
test-acceptance:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' bin/behat -c tests/Acceptance/behat.yml --colors ${parameters}"

.PHONY: test-this-acceptance
test-this-acceptance:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' bin/behat -c tests/Acceptance/behat.yml --tags '@this' --colors ${parameters}"

.PHONY: load-fixtures
load-fixtures:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:fixtures:load --env=test -n"

.PHONY: reset-test-db
reset-test-db:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:drop -f || true"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:create"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:migrations:migrate --no-interaction"