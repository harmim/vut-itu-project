WWW_DIR := www
HTML_DIR := $(WWW_DIR)/html


.PHONY: html
html: $(HTML_DIR)/index.html
	open $<
