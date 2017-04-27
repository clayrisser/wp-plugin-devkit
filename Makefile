CWD := $(shell readlink -en $(dir $(word $(words $(MAKEFILE_LIST)),$(MAKEFILE_LIST))))
PLUGIN_NAME := plugin-name

.PHONY: all
all: fetch_dependancies

.PHONY: start
start:
	docker run --name some-mariadb --rm -d -e MYSQL_ROOT_PASSWORD=hellodocker mariadb:latest
	docker run --name some-instant-wordpress --rm --link some-mariadb:mysql -p 8080:80 -v $(CWD)/$(PLUGIN_NAME):/var/www/html/wp-content/plugins/$(PLUGIN_NAME) jamrizzi/instant-wordpress:latest

.PHONY: init
init:
	php init.php

.PHONY: kill
kill:
	docker stop some-mariadb some-wordpress
	docker rm some-mariadb some-wordpress

.PHONY: fetch_dependancies
fetch_dependancies: docker
	$(info fetched dependancies)
.PHONY: docker
docker:
ifeq ($(shell whereis docker), $(shell echo docker:))
	curl -L https://get.docker.com/ | bash
endif
	$(info fetched docker)
