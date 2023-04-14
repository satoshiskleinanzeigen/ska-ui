.PHONY: skaui docker-pull

build: skaui

docker-pull:
	docker pull php:8.2-apache

skaui: docker-pull
	docker build --no-cache -t skaui -f Dockerfile .