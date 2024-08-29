# Use an official PHP image as the base
FROM php:8.3-fpm-alpine

# Install required extensions
RUN apk add --no-cache composer libpng-dev libjpeg-turbo-dev freetype-dev gd-dev icu zlib-dev libzip-dev

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-interaction --no-ansi

# Copy the application code
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose the container's port
EXPOSE 80

# Command to run when the container starts
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]