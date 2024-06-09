# Use the official PHP image as the base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev

RUN apt-get update && apt-get install -y apt-transport-https ca-certificates

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user
RUN groupadd -g 1000 symfonygroup && \
    useradd -u 1000 -g symfonygroup -m -s /bin/bash symfonyuser

# Change ownership of the application directory
RUN chown -R symfonyuser:symfonygroup /var/www/html
RUN chmod -R 775 /var/www/html

# Switch to non-root user
USER 1000

# Copy existing application directory contents
COPY . /var/www/html


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
