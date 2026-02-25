# Using .env.docker for environment variables

ENV_FILE := .env.docker
COMPOSE := docker compose --env-file $(ENV_FILE)

# Build all containers
build:
	$(COMPOSE) build

# Start all containers in detached mode
up:
	$(COMPOSE) up -d

# Start containers and attach to logs in real time
up-logs:
	$(COMPOSE) up

# Stop all containers
down:
	$(COMPOSE) down

# Rebuild all containers (no cache) and start in detached mode
rebuild:
	$(COMPOSE) down
	$(COMPOSE) up -d --build

# Run Laravel Horizon manually
horizon:
	$(COMPOSE) exec php php artisan horizon

# Run Laravel scheduler manually
schedule:
	$(COMPOSE) exec php php artisan schedule:run

# Run migrations
migrate:
	$(COMPOSE) exec php php artisan migrate

# Run migrations rollback
migrate_rollback:
	$(COMPOSE) exec php php artisan migrate:rollback

# Run tests
test:
	$(COMPOSE) exec php php artisan test
