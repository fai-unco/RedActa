#!/bin/bash

#Regenera la lista de todas las clases que deben ser incluidas en el proyecto
composer dump-autoload
#Descarga el navegador chromium para la generación de pdf
./vendor/bin/snappdf download
#Genera key y la almacena en api/.env
php artisan key:generate
sleep 15
#Crea tablas en base de datos
php artisan migrate --seed
#Inicia el servicio de Apache
apache2ctl -D FOREGROUND

