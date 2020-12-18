FROM php:7.4-cli-alpine

# Install required librairies
RUN apk update && apk add autoconf g++ make git

RUN git --version

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./ /app

WORKDIR /app
