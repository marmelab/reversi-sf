.PHONY: run stop expose test lint

# Run ###########################

run:
	docker-compose up -d
	docker-compose exec reversi_sf_php composer install -d /app; true
	docker-compose exec reversi_sf_php chown -R www-data:www-data /app/var/cache && rm -rf /app/var/cache/*
	docker-compose exec reversi_sf_php chown -R www-data:www-data /app/var/logs && rm -rf /app/var/logs/*
	docker-compose exec reversi_sf_php php /app/bin/console doctrine:schema:drop --force 2>/dev/null; true
	docker-compose exec reversi_sf_php php /app/bin/console doctrine:schema:update --force 2>/dev/null; true

# Stop ##########################

stop:
	docker-compose down

# Expose (for webhooks access) ##

expose:
	ngrok start app --config=ngrok.yml

# Tests #########################

test:
	phpunit -vvv

# Lint ##########################

lint:
	php-cs-fixer fix src/ --level=psr2
