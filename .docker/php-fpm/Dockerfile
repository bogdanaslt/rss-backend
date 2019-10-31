FROM php:7.3.8-fpm

RUN apt update
RUN apt install -y git unzip libzip-dev

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/app
EXPOSE 9000
USER root
RUN php -r 'readfile("https://getcomposer.org/installer");' > composer-setup.php \
	&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
	&& rm -f composer-setup.php \
	&& chown www-data:www-data /var/app

RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip

COPY init.sh /init.sh
COPY docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh