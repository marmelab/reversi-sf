.PHONY: run expose test

# Run ###########################

run:
	docker-compose up

# Expose (for webhooks access) ##

expose:
	ngrok start app --config=ngrok.yml

# Tests #########################

test:
	phpunit -vvv
