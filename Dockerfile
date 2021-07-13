FROM php:8.0-cli
RUN apt-get update && apt-get install -y curl
COPY . /usr/src/http-client-example
WORKDIR /usr/src/http-client-example
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-interaction --no-progress --no-scripts
CMD [ "php", "./index.php" ]
