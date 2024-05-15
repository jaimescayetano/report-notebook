
# TaskMaster


## Architecture

![App Screenshot](https://raw.githubusercontent.com/jaimescayetano/images/main/projects/task_master/model-task-master-white.png)

## Database - Phase 01

![App Screenshot](https://raw.githubusercontent.com/jaimescayetano/images/a8323540a35d8a75e27783acf86b33a8084c1c0f/projects/task_master/Task-Master%20(Chen%20diagram).svg)

## Development environment

```
git clone git@github.com:jaimescayetano/task-master.git
```

```
cd task-master
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

Once you have completed all these steps, you will be able to access the website from `http://localhost:8010/admin/login`

## Authors

- [@jaimescayetano](https://www.github.com/jaimescayetano)

## License

[MIT](https://choosealicense.com/licenses/mit/)