# budget-flow-api

## Running

### Copy .env file & generate key
Copy `.env.example` file and generate application key:
```sh
cp .env.example .env
php artisan key:generate
```

### Start all the containers in the background
```sh
docker compose up -d
```

### Use Laravel Telescope (optional)
```sh
php artisan telescope:install
```

### Generate API documentation with Scribe (optional)
```sh
php artisan scribe:generate
```

## Generating static documentation
Generate documentation with `php artisan scribe:generate`, 

## Project structure

- `Dockerfile` and nginx configuration for the application are located in `docker/`
- The application itself is located in `src/`
