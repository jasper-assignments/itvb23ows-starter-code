FROM php:8.3-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 8000

RUN apt-get update && apt-get install zip -y
RUN docker-php-ext-install mysqli

WORKDIR /app
COPY . /app

RUN composer install

CMD [ "php", "-S", "0.0.0.0:8000", "-t", "public" ]
