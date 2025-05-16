#!/bin/bash
# Instalar dependencias
composer install

# Ejecutar migraciones si es necesario
# php artisan migrate --force

# Iniciar servicios en segundo plano
php artisan serve --host=0.0.0.0 --port=8001 &
php artisan reverb:start --host=0.0.0.0 --port=8002 &
php artisan queue:work &

# Mantener el contenedor en ejecución usando sleep infinity en lugar de tail
echo "Todos los servicios se han iniciado"
sleep infinity