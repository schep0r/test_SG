# Environment

``docker-compose up --build``

``docker exec -it php-sg bash``

# Backend

``cd api``

``composer install``


create DB tables

``vendor/bin/doctrine orm:schema-tool:create``

Create demo Users:

``php config/fixtures.php``

# Tests

``vendor/bin/phpunit``

# Console Command

``php bin/console app:send:money-to-bank [<limit>]``

if limit was not set, it will use default value=2

# Frontend

can be found with URL ``http://localhost:8080/`` 