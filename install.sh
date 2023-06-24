#!/bin/bash

#Crea el archivo api/.env
touch api/.env
cp api/.env.example api/.env
#Otorga permisos de escritura a otros usuarios (para poder modificar archivos desde dentro del contenedor)
chmod -R 755 api/storage api/bootstrap
#Otorga permisos de ejecución a otros usuarios
chmod 701 api/run.sh
#Crea directorios donde se guardarán las dependencias 
mkdir frontend/node_modules api/vendor
#Actualiza el archivo api/.env con los parámetros de la base de datos que corresponden
sed -ri -e 's!DB_HOST=127.0.0.1!DB_HOST=database!g' api/.env
sed -ri -e 's!DB_DATABASE=laravel!DB_DATABASE=redacta!g' api/.env
sed -ri -e 's!DB_PASSWORD=!DB_PASSWORD=root!g' api/.env
#Setea en el archivo api/.env el directorio donde se almacenaran los archivos estáticos
sed -ri -e 's!STATIC_FILES_DIRECTORY=/!STATIC_FILES_DIRECTORY=/var/www!g' api/.env
