.PHONY: run expose test

# Run ###########################

run:
	docker-compose up

# Expose (for webhooks access) ##

expose:
	ngrok run app --config=ngrok.yml

# Tests #########################

test:
	phpunit -vvv
