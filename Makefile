.POSIX:
export ROOTDIR ?= $(eval ROOTDIR := $(shell git rev-parse --show-toplevel))$(ROOTDIR)
include $(ROOTDIR)/make.mk

.DEFAULT_GOAL := lint

ASDF_VERSION ?= v0.18.0
.PHONY: prepare prepare/asdf prepare/cloc prepare/php prepare/composer
prepare: sudo
	@command -v asdf >/dev/null 2>&1 || $(MAKE) prepare/asdf
	@command -v $(CLOC) >/dev/null 2>&1 || $(MAKE) prepare/cloc
	@command -v $(PHP) >/dev/null 2>&1 || $(MAKE) prepare/php
	@command -v $(COMPOSER) >/dev/null 2>&1 || $(MAKE) prepare/composer
	@awk '!/^#/ && NF {print $$1}' .tool-versions | \
		while read t; do asdf plugin add "$$t" 2>/dev/null || true; done
	@rcfile=$$(mktemp); \
		{ asdf install 2>&1; echo $$? >$$rcfile; } | grep --line-buffered -v 'is already installed' || true; \
		rc=$$(cat $$rcfile); rm -f $$rcfile; exit $$rc
	@$(COMPOSER) install
prepare/asdf:
	@command -v brew >/dev/null 2>&1 && brew install asdf || { \
		o=$$(uname | tr A-Z a-z); a=$$(uname -m | sed 's/x86_64/amd64/;s/aarch64/arm64/'); \
		curl -fsSL "https://github.com/asdf-vm/asdf/releases/download/$(ASDF_VERSION)/asdf-$(ASDF_VERSION)-$$o-$$a.tar.gz" \
			| $(SUDO) tar -xz -C /usr/local/bin asdf; \
	}
prepare/cloc:
	@$(PKG_INSTALL) cloc
prepare/php:
	@$(PKG_INSTALL) php
prepare/composer:
	@$(PKG_INSTALL) composer

.PHONY: configure
configure:
	@for cmd in asdf $(PHP) $(COMPOSER) $(BATS) $(SHFMT) $(CLOC) docker; do \
		command -v $$cmd >/dev/null 2>&1 || { echo "$$cmd is missing, run \`make prepare\`"; exit 1; }; \
	done

.PHONY: deps
deps: configure
	@$(COMPOSER) install

.PHONY: init
init: configure
	@$(PHP) tools.php init

# Shared by format and lint
_SHFILES = find tests -type f \( -name '*.sh' -o -name '*.bats' \) -print0

.PHONY: format
format: deps
	@$(PHPCBF) || [ $$? -eq 1 ]
	@$(_SHFILES) | xargs -0 $(SHFMT) -w

.PHONY: lint
lint: deps
	@$(PHPCS)
	@$(_SHFILES) | xargs -0 $(SHFMT) -d

.PHONY: test/e2e
test/e2e: deps
	@cd tests && export PROJECT_ROOT=$(ROOTDIR) && \
		trap './teardown.sh' EXIT INT TERM; \
		./setup.sh && $(BATS) .

.PHONY: count
count: configure
	@$(CLOC) --vcs=git

.PHONY: docker docker/%
docker: configure FORCE
	@$(MAKE) -C docker
docker/%: configure FORCE
	@$(MAKE) -C docker $*

.PHONY: clean
clean:
	@rm -rf $(MAKEDIR)

.PHONY: purge
purge: clean
	@$(GIT) clean -fxd
