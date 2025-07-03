.PHONY: start stop restart

# Variables
ENV_FILE=.env
DOCKER_COMPOSE=docker compose
PORT ?= 8080

start:
	@echo "Starting environment..."
	@if [ -f $(ENV_FILE) ]; then \
		PORT=$(PORT) $(DOCKER_COMPOSE) up -d; \
	else \
		PORT=$(PORT) $(DOCKER_COMPOSE) up -d; \
	fi

stop:
	@echo "Stopping environment..."
	$(DOCKER_COMPOSE) down

restart: stop start


.PHONY: test phpstan phpcsfixer phpcsfixer-fix
DOCKER_COMPOSE=docker compose
APP_ENV=test

test:
	$(DOCKER_COMPOSE) run -e APP_ENV=$(APP_ENV) app-test vendor/bin/phpunit

phpstan:
	$(DOCKER_COMPOSE) run -e APP_ENV=$(APP_ENV) app-test vendor/bin/phpstan analyse -c /app/phpstan.test.dist.neon

phpcsfixer:
	$(DOCKER_COMPOSE) run -e APP_ENV=$(APP_ENV) app-test vendor/bin/ecs

phpcsfixer-fix:
	$(DOCKER_COMPOSE) run -e APP_ENV=$(APP_ENV) app-test vendor/bin/ecs --fix