.POSIX:
ROOTDIR ?= $(eval ROOTDIR := $(shell git rev-parse --show-toplevel))$(ROOTDIR)
include $(ROOTDIR)/make.mk

.DEFAULT_GOAL := up

COMPOSE_ARGS ?= -f $(ROOTDIR)/docker/compose.yaml

.PHONY: up up/%
up:
	@$(COMPOSE) $(COMPOSE_ARGS) up
up/%:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile $* up

.PHONY: up-d up-d/%
up-d:
	@$(COMPOSE) $(COMPOSE_ARGS) up -d --wait
up-d/%:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile $* up -d --wait

# Idempotent first-time WordPress install (skips the browser setup wizard).
.PHONY: provision
provision: up-d
	@$(COMPOSE) $(COMPOSE_ARGS) run --rm -T wpcli sh -c 'wp core is-installed 2>/dev/null || wp core install \
		--url="$(or $(WP_URL),http://localhost:8888)" \
		--title="$(or $(WP_TITLE),WP Plugin DevKit)" \
		--admin_user="$(or $(WP_ADMIN_USER),admin)" \
		--admin_password="$(or $(WP_ADMIN_PASSWORD),wordpress)" \
		--admin_email="$(or $(WP_ADMIN_EMAIL),admin@example.com)" \
		--skip-email'

.PHONY: down down/%
down:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile dev --profile cli down --volumes --remove-orphans
down/%:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile $* down --volumes --remove-orphans

.PHONY: logs logs/%
logs:
	@$(COMPOSE) $(COMPOSE_ARGS) logs -f
logs/%:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile $* logs -f

.PHONY: ps ps/%
ps:
	@$(COMPOSE) $(COMPOSE_ARGS) ps
ps/%:
	@$(COMPOSE) $(COMPOSE_ARGS) --profile $* ps
