# budget-flow-api

## Running
First, copy `.env.example` file, generate application and JWT key and tune other settings to your likings:
```sh
cp .env.example .env
php artisan key:generate
```
Run everything with `docker compose up -d`  
(`-d` is optional, but it runs everything in background)

## Project structure

- `Dockerfile` and nginx configuration for the application are located in `docker/`
- The application itself is located in `src/`
