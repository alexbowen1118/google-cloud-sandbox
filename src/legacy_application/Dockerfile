FROM php:7.4-apache

ENV TZ EST5EDT
# Set PHP configuration options.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Update package indexes.
RUN apt update
RUN apt upgrade -y

# Install the mariadb client to facilitate interaction with the db.
RUN apt install mariadb-client -y

# Install and enable mysqli.
RUN docker-php-ext-install mysqli


ENV APACHE_DOCUMENT_ROOT /opt/library/prd/WebServer/Documents/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# Copy over the apache config files.
COPY config/apache2.conf /etc/apache2/
COPY config/000-default.conf /etc/apache2/sites-available/
