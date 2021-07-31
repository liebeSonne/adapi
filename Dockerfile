
FROM php:7.3-cli

RUN apt-get update
RUN apt-get install -y curl
RUN apt-get install -y git
RUN apt-get install -y sqlite3

RUN apt-get install -y zip unzip

# Install Composer
 RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

RUN composer install

RUN composer build

EXPOSE 8080

# CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
CMD ["composer", "php-server"]