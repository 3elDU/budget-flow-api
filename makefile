start:
	cd src && docker compose up

.PHONY: artisan

artisan:
	docker-compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

freshseed:
	cd src && php artisan migrate:fresh --seed
