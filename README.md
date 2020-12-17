# products_backend

## Project setup
```
composer install
```

### Configure .env file
Create .env file, copy all from .env.example, configure new .env file as your server environment.

### Generate Key
```
php artisan key:generate
```

### Generate JWT Secret Key
```
php artisan jwt:secret
```

### Config Cache
```
php artisan config:cache
```

### Migrate Database
```
php artisan migrate
```

### Serve in local environment
```
php artisan serve
```

### Run Products Frontend
After successfully serving this application in your local environment, Run [Products Frontend](https://github.com/mdasifmallik/products_frontend).
