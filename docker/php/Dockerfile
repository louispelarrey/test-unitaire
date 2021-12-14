FROM php:8.1-fpm-alpine
RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/bin
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql
ENV PATH /root/.composer/vendor/bin:$PATH
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install ;  php-fpm
EXPOSE 9000
RUN chown -R www-data:www-data /var/www