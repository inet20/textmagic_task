USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm textmagic_task_php
DC_RUN_REDIS = ${DC} run --rm textmagic_task_redis
DC_EXEC = ${DC} exec textmagic_task_php

PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: down build install up wait migration fixtures clearL2C success-message console ## Initialize environment

build: ## Build services.
	${DC} build $(c)

up: ## Create and start services.
	${DC} up -d $(c)

stop: ## Stop services.
	${DC} stop $(c)

start: ## Start services.
	${DC} start $(c)

down: ## Stop and remove containers and volumes.
	${DC} down -v $(c)

restart: stop start ## Restart services.

console: ## Login in console.
	${DC_EXEC} /bin/bash

install:
	${DC_RUN} composer install

wait:
	sleep 1

migration:
	${DC_RUN} php bin/console do:mi:mi -n

fixtures:
	${DC_RUN} php bin/console do:fi:lo -n

clearL2C:
	${DC_RUN_REDIS} redis-cli -h textmagic_task_redis flushall

success-message:
	@echo "You can now access the application at http://localhost:8080"
