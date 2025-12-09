build:
	make stop > /dev/null 2>&1
	docker pull ghcr.io/elph-studio/base-php-local:8.5
	docker-compose build
	make start

start up:
	docker-compose up -d

stop down:
	docker-compose down

list:
	docker-compose ps

list-all:
	docker ps -a

# Exec containers
enter php exec-app:
	docker-compose exec app sh

# Logs
log logs:
	docker-compose logs app

tail follow:
	docker-compose logs --follow app
