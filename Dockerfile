# Usar imagen oficial de PHP con menos peso (si es posible)
FROM php:8.2-cli

# Instalar dependencias en una sola capa y limpiar después
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    procps \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer de forma segura (con verificación)
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=cmposer \
    --version=2.8.9 \
    --sha-256=8e8829ec2b97fcb05158236984bc252bef902e7b8ff65555a1eeda4ec13fb82b

WORKDIR /code

# Copiar solo archivos necesarios para instalar dependencias primero
COPY composer.json composer.lock ./

# Instalar dependencias de Composer (sin dev)
# RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copiar el resto del código DESPUÉS de instalar dependencias
COPY . .

# Script de inicio
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exponer puertos
EXPOSE 8001
EXPOSE 8002

CMD ["/start.sh"]