FROM php:8.1-apache

ENV TZ EST5EDT

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN sed -ri -e 's/;?post_max_size.*/post_max_size=2048M/' "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's/;?upload_max_filesize.*/upload_max_filesize=2048M/' "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's/;?max_execution_time.*/max_execution_time=600/' "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's/;?memory_limit.*/memory_limit=1024M/' "$PHP_INI_DIR/php.ini"

# XDebug
RUN pecl install xdebug \
&& docker-php-ext-enable xdebug \
&& echo "\n\
[xdebug]\n\
xdebug.mode=\${XDEBUG_MODES} \n\
xdebug.start_with_request=yes \n\
xdebug.discover_client_host=0 \n\
xdebug.client_host=host.docker.internal \n\
xdebug.log=/tmp/xdebug/xdebug.log \n\
xdebug.log_level=\${XDEBUG_LOG_LEVEL}" >> "$PHP_INI_DIR/php.ini"

# Update package indexes
RUN apt update
RUN apt-get install -y mariadb-client libzip-dev zip unzip libcurl4-openssl-dev

RUN docker-php-ext-install zip pdo_mysql

# php-curl
RUN docker-php-ext-install curl

RUN a2enmod rewrite
RUN a2enmod actions

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./composer* /var/www/
RUN composer install

COPY . /var/www/

RUN chown -R 1000:1000 /var/www
