SHELL := /bin/bash

.PHONY: *

clean: web/themes/custom/phpsouthwales/build web/themes/custom/phpsouthwales/node_modules
	rm -fr vendor
	rm -fr web/themes/custom/phpsouthwales/{build,node_modules}

config-export:
	symfony php vendor/bin/drush config:export -y

features-export:
	symfony php vendor/bin/drush features:export -y \
		phpsw_event

drupal-install: vendor web/sites/default/settings.php
	symfony php vendor/bin/drush site:install -y \
		--existing-config \
		--no-interaction && \
	symfony php vendor/bin/drush cache:rebuild

drupal-post-install: web/sites/default/settings.php
	symfony php vendor/bin/drush migrate:import --all
	symfony php vendor/bin/drush core:cron

init: .env.example
	make vendor
	cp .env.example .env

test-phpcs:
	symfony php vendor/bin/phpcs -v \
		--standard=Drupal \
		--extensions=php,module,inc,install,test,profile,theme,pcss,info,txt,md \
		--ignore=node_modules,*/tests/* \
		web/modules/custom \
		web/themes/custom

test-phpstan:
	symfony php vendor/bin/phpstan analyze

test-phpunit:
	symfony php vendor/bin/phpunit web/modules/custom --verbose --testdox

test: test-phpcs test-phpstan test-phpunit

theme-build: web/themes/custom/phpsouthwales/package.json web/themes/custom/phpsouthwales/package-lock.json
	cd web/themes/custom/phpsouthwales && \
	npm install && \
	npm run prod

	symfony php vendor/bin/drush cache:rebuild

vendor: composer.json
	symfony composer validate
	symfony composer install
