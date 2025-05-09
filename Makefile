test-db-reset:
	APP_ENV=test php bin/console doctrine:database:drop --force --if-exists
	APP_ENV=test php bin/console doctrine:database:create --if-not-exists
	APP_ENV=test php bin/console doctrine:migrations:migrate --no-interaction

test:
	make test-db-reset
	php bin/phpunit
