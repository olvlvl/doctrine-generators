# customization

PACKAGE_NAME = olvlvl/doctrine-generators
PACKAGE_VERSION = 0.1
PHPUNIT_VERSION = phpunit-6.phar
PHPUNIT = build/$(PHPUNIT_VERSION)
PHPUNIT_COVERAGE=phpdbg -qrr $(PHPUNIT) -d memory_limit=-1

# do not edit the following lines

all: $(PHPUNIT) vendor

usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

vendor:
	@COMPOSER_ROOT_VERSION=$(PACKAGE_VERSION) composer install

update:
	@COMPOSER_ROOT_VERSION=$(PACKAGE_VERSION) composer update

autoload: vendor
	@composer dump-autoload

$(PHPUNIT):
	mkdir -p build
	wget https://phar.phpunit.de/$(PHPUNIT_VERSION) -O $(PHPUNIT)
	chmod +x $(PHPUNIT)

test: all
	@$(PHPUNIT)

test-container:
	@docker-compose \
		-f ./tests/docker-compose.yml \
		-p doctrine-generators-test \
		run --rm app sh

test-coverage: all
	@mkdir -p build/coverage
	@$(PHPUNIT_COVERAGE) --coverage-html build/coverage

test-coveralls: all
	@mkdir -p build/logs
	COMPOSER_ROOT_VERSION=$(PACKAGE_VERSION) composer require satooshi/php-coveralls
	@$(PHPUNIT_COVERAGE) --coverage-clover build/logs/clover.xml
	php vendor/bin/php-coveralls -v

doc: vendor
	@mkdir -p build/docs
	@apigen generate \
	--source lib \
	--destination build/docs/ \
	--title "$(PACKAGE_NAME) v$(PACKAGE_VERSION)" \
	--template-theme "bootstrap"

clean:
	@rm -fR build
	@rm -fR vendor
	@rm -f composer.lock

.PHONY: all autoload doc clean test test-coverage test-coveralls update
