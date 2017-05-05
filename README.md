Team Organizer
==============
Prosta aplikacja dla organizacji zespołu w sprawach wewnętrznych

Instalacja
----------
```
docker-compose build
composer install --ignore-platform-reqs
```
Composer nie powinien być uruchamiany wewnątrz kontenera

Uruchamianie
------------
```
docker-compose run -p 8080:8080 --rm app php -S 0.0.0.0:8080 -t public
```