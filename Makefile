PRODUCTION := 0

NODE_DIR := node_modules
WWW_DIR := www
NODE_BIN_DIR := $(NODE_DIR)/.bin
HTML_DIR := $(WWW_DIR)/html

DOCKER_NODE := docker-compose exec node


.PHONY: install
install: assets


.PHONY: assets
assets: npm grunt


.PHONY: npm
npm: docker-compose-node
ifeq ($(PRODUCTION), 0)
	$(DOCKER_NODE) npm install
else
	$(DOCKER_NODE) npm install --no-dev
endif


.PHONY: grunt
grunt: npm
ifeq ($(PRODUCTION), 0)
	./$(NODE_BIN_DIR)/grunt development
else
	./$(NODE_BIN_DIR)/grunt production
endif



.PHONY: html
html: $(HTML_DIR)/index.html
	open $<


.PHONY: clean
clean:
	git clean -xdff $(NODE_DIR) $(WWW_DIR)


.PHONY: docker-compose-node
docker-compose-node:
	docker-compose up -d node
