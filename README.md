
# TaskMaster


## Screenshots

![App Screenshot](https://raw.githubusercontent.com/jaimescayetano/images/main/projects/task_master/model-task-master.png?token=GHSAT0AAAAAACMXWT7WUSK5TU44CDK7I5M6ZSBRAEA)


## Development environment

Architecture


```
git clone 
```

```
docker-compose up
```

Create the .env and configure the environment variables
```
cp .env.example .env
```

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=task_master
DB_USERNAME=root
DB_PASSWORD=root
```

In the 'task_master' container
```
composer install
```

```
php artisan migrate
```

```
php artisan make:filament-user
```

## Authors

- [@jaimescayetano](https://www.github.com/jaimescayetano)

## License

[MIT](https://choosealicense.com/licenses/mit/)