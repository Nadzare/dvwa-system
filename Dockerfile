FROM --platform=linux/amd64 php:8.2-apache

# Install system dependencies
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/* && \
    apt-get update --allow-releaseinfo-change && \
    apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod headers

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/app
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-enabled/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-enabled/*.conf

# Create app directory
WORKDIR /var/www/html

# Copy application files
COPY app /var/www/html/app
COPY assets /var/www/html/assets
COPY db /var/www/html/db

# Set proper permissions and fix line endings
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 777 /var/www/html/app && \
    find /var/www/html -type f -name "*.sh" -exec dos2unix {} \; && \
    find /var/www/html -type f -name "*.sh" -exec chmod +x {} \;

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/login_id.php || exit 1

EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
