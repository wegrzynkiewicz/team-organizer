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

Uruchamianie serwera wbudowanego z xdebugiem
--------------------------------------------
```
docker-compose run -p 8080:8080 --rm app php -S 0.0.0.0:8080 -t public -dxdebug.remote_enable=1 -dxdebug.remote_host=192.168.0.200
```

Uruchamianie serwera apache z xdebugiem
```
docker-compose up -d team_organizer_apache_xdebug 
```