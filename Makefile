.PHONY: setup
setup:
	@docker-compose up -d --build
	@docker-compose exec app composer install
	@echo "\n\033[92mSetup finished successfully.\033[0m\n"

.PHONY: up
up:
	@docker-compose up -d
	@echo "\033[94mUp and running!\033[0m\n"

.PHONY: down
down:
	@docker-compose down

.PHONY: console
console:
	@docker-compose exec app $(filter-out $@,$(MAKECMDGOALS))

.PHONY: start
start:
	@docker-compose exec app php bin/console vending-machine:start

%:
	@:
