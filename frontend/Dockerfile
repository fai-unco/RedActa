FROM node:16.14.2
WORKDIR /app
#Copia dentro del contenedor los archivos package*.json
COPY ./package*.json .
#Instalación de dependencias
RUN npm install
RUN npm install -g @angular/cli@13.3.3
#Copia el contenido del proyecto dentro del contenedor 
COPY . .
#Ejecuta el servidor de desarrollo
CMD ng serve --host=0.0.0.0 --disable-host-check
