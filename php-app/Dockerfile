FROM php:8.1-cli

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

COPY . .

CMD ["php", "-S", "0.0.0.0:8080"]