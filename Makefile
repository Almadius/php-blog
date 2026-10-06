# make        — собрать контейнеры и заполнить базу
# make up     — поднять уже собранный проект
# make seed   — заново заполнить таблицы
# make down   — остановить контейнеры
# make reset  — удалить данные и собрать проект заново

.DEFAULT_GOAL := init

.PHONY: init up seed down reset config

ifeq ($(OS),Windows_NT)
SHELL := cmd.exe
.SHELLFLAGS := /C
COPY_CONFIG = if not exist config/database.php copy config/database.example.php config/database.php
else
COPY_CONFIG = test -f config/database.php || cp config/database.example.php config/database.php
endif

init: up seed

config:
	$(COPY_CONFIG)

up: config
	composer install --no-interaction
	docker compose up -d --build --wait

seed:
	docker compose exec -T app php database/seed.php

down:
	docker compose down

reset:
	docker compose down -v
	$(MAKE) init
