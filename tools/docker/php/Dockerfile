# Base
FROM php:8.1.12-apache-bullseye

# Define workdir
WORKDIR /var/www/html

# Softwares
RUN apt-get update && apt-get install -y \
    acl \
    apt-utils \
    build-essential \
    git \
    htop \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpq-dev \
    libpng-dev \
    librabbitmq-dev \
    libsqlite3-dev \
    libssh-dev \
    libzip-dev \
    libwebp-dev \
    nano \
    pkg-config \
    sqlite3 \
    supervisor \
    unzip \
    wget \
    zip 

# Apache modules
RUN a2enmod \
    rewrite \
    expires \
    headers
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Extension PHP
RUN docker-php-ext-install \
    gd \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    bcmath \
    zip \
    exif \
    opcache \
    iconv \
    sockets \
    pcntl \
    intl

# Add php.ini custom config
COPY ./tools/docker/config/php.ini /usr/local/etc/php/

# Add vhost
COPY ./tools/docker/config/default-vhost.conf /etc/apache2/sites-available/
RUN a2dissite 000-default.conf \
    && a2ensite default-vhost.conf 

# Add Apache user
RUN useradd -r --create-home -u 998 1000 \
    && adduser 1000 root \
    && adduser 1000 www-data \
    && chown -R www-data:www-data /var/www

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && chmod +x /root/.symfony5/bin/symfony \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Composer
COPY ./tools/scripts/install-composer.sh /tmp
RUN bash /tmp/install-composer.sh

# Sqlite
RUN mkdir /var/db \
    && /usr/bin/sqlite3 /var/db/app_test.db

# Redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# APCU
RUN pear config-set http_proxy "${http_proxy}" \
    && pecl channel-update pecl.php.net \
    && pecl install apcu
RUN echo "extension=apcu.so\napc.enable_cli=on" > /usr/local/etc/php/conf.d/apcu.ini

# Aliases
RUN echo "alias arc='service apache2 reload'" >> /root/.bashrc \
    && echo "alias epi='nano /usr/local/etc/php/php.ini'" >> /root/.bashrc

# Clean the mess
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add files
#COPY . .

CMD ["apache2-foreground"]
