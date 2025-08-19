SHELL=/bin/bash

PLATFORM := $(shell uname -s)

.PHONY: install
install:
	@if [ ! -f "docker-compose.override.yml" ]; then \
		ARCH=$$(uname -m); \
		if [ "$$ARCH" = "arm64" ] || [ "$$ARCH" = "aarch64" ]; then \
			echo "Detected ARM64 architecture, using arm64 configuration"; \
			cp docker-compose.override.arm64.yml docker-compose.override.yml; \
		elif [ "$$ARCH" = "x86_64" ]; then \
			echo "Detected x86_64 architecture, using amd64 configuration"; \
			cp docker-compose.override.amd64.yml docker-compose.override.yml; \
		fi \
	fi
	docker compose build
	docker compose up -d
	docker compose exec php-fpm composer install --no-interaction
	docker compose exec php-fpm bin/console doctrine:migrations:migrate --no-interaction
	sleep 3 # Wait for rabbit before setup transports
	docker compose exec php-fpm bin/console messenger:setup-transports --no-interaction

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

## Runs unit integration tests
.PHONY: test-integration
test-integration:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' bin/phpunit -c tests/Integration/phpunit.xml --testdox ${parameters}"

.PHONY: load-fixtures
load-fixtures:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:fixtures:load --env=test -n"

.PHONY: reset-test-db
reset-test-db:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:drop -f || true"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:create"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:migrations:migrate --no-interaction"
