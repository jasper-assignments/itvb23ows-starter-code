FROM php:8.3-cli

EXPOSE 8000

RUN docker-php-ext-install mysqli

COPY ./src /src

WORKDIR /src

CMD [ "php", "-S", "0.0.0.0:8000" ]
