FROM php:8.1-apache

#Define variable de entorno con el path del directorio que publicará Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

#Instala librerias del sistema operativo requeridas por la aplicación
RUN apt-get update && \
        apt-get install -y \
        libzip-dev \
        zip \
        git \
        #libnss3 \
        #libatk1.0-0 \
        #libatk-bridge2.0-0 \
        #libcups2
        #libdrm
        ca-certificates \
        fonts-liberation \ 
        libappindicator3-1 \
        libasound2 \
        libatk-bridge2.0-0 \
        libatk1.0-0 \
        libc6 \
        libcairo2 \
        libcups2 \
        libdbus-1-3 \
        libexpat1 \
        libfontconfig1 \
        libgbm1 \
        libgcc1 \
        libglib2.0-0 \
        libgtk-3-0 \
        libnspr4 \
        libnss3 \
        libpango-1.0-0 \
        libpangocairo-1.0-0 \
        libstdc++6 \
        libx11-6 \
        libx11-xcb1 \
        libxcb1 \
        libxcomposite1 \
        libxcursor1 \
        libxdamage1 \
        libxext6 \
        libxfixes3 \
        libxi6 \
        libxrandr2 \
        libxrender1 \
        libxss1 \
        libxtst6 \
        lsb-release \
        wget \
        xdg-utils\
        poppler-utils

RUN a2enmod rewrite 
RUN docker-php-ext-install pdo_mysql zip

#Instala Composer (gestor de dependencias de PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Se establece el directorio que publicará Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY composer.json composer.lock .

#Instalación de dependencias 
RUN composer install --no-scripts --no-autoloader
#Al inicio de la ejecución del contenedor se lanza el script run.sh
CMD ./run.sh

