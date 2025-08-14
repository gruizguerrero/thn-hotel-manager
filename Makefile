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