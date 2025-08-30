FROM php:8.3-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libxml2-dev \
    zip \
    imagemagick \
    libmagickwand-dev \
    libzip-dev
 
# Instala e habilita a extensão imagick (PECL)
RUN pecl install imagick \
    && docker-php-ext-enable imagick
    
# Extensões do PHP necessárias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip

# Configurar e instalar extensão GD com suporte a freetype, jpeg e webp
RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-webp \
    --with-freetype \
 && docker-php-ext-install -j$(nproc) gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]