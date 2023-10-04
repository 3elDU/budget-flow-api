# budget-flow-api

## Running
Copy `.env.example` file and generate application key:
```sh
cp .env.example .env
php artisan key:generate
```
Run everything with `docker compose up -d`  
(`-d` is optional, but it starts containers in the background)

## Project structure

- `Dockerfile` and nginx configuration for the application are located in `docker/`
- The application itself is located in `src/`
