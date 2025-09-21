# Dockerfile (Version Finale Complète)
FROM php:8.2-fpm

# Installe les dépendances système et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libjpeg-dev zlib1g-dev libxml2-dev \
    libxslt-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql zip opcache gd xml xsl \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# -- AJOUTE CETTE NOUVELLE SECTION --
# Installe Node.js et NPM
RUN apt-get update && apt-get install -y nodejs npm
# ------------------------------------

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définit le dossier de travail
WORKDIR /srv/app

# Expose le port de php-fpm
EXPOSE 9000
CMD ["php-fpm"]