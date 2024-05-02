# Use an official PHP runtime as a base image
FROM php:8.2-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

## Install dependencies
RUN apt-get update && \
apt-get install -y \
libzip-dev \
zip

RUN docker-php-ext-install mysqli

# Expose port 80 to allow outside access to your web app
EXPOSE 80

# Start the Apache server when the container launches
CMD ["apache2-foreground"]
