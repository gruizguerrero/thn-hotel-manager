SHELL=/bin/bash

PLATFORM := $(shell uname -s)

## Runs acceptance tests
.PHONY: test-acceptance
test-acceptance:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' bin/behat -c tests/Acceptance/behat.yml --colors ${parameters}"