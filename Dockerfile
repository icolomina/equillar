FROM ubuntu:latest

# Set non-interactive mode for apt
ARG DEBIAN_FRONTEND=noninteractive

# Update and install required packages in a single RUN command
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
        apt-utils \
        locales \
        software-properties-common \
        nginx \
        sqlite \
        curl \
        git \
        openssl \
        postgresql-client \
        zip \
        unzip

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && \
    apt-get install -y \ 
    php8.3 \
    php8.3-fpm \
    php8.3-xml \
    php8.3-mbstring \
    php8.3-curl \
    php8.3-gmp \
    php8.3-gd \
    php8.3-pgsql  \
    php8.3-bcmath \
    php8.3-zip \
    php8.3-intl 

RUN apt-get clean 


# Set the locale
RUN locale-gen es_ES.UTF-8
RUN update-locale LANG=es_ES.UTF-8
ENV LANG=es_ES.UTF-8 LC_ALL=es_ES.UTF-8

# Set working directory
WORKDIR /var/www/equillar

# Copy application files
COPY . .

# Assign write permissions to www-data group so that the web server can write to necessary directories
RUN chgrp -R www-data /var/www/equillar && chmod -R 775 /var/www/equillar

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js using NVM
ENV NODE_VERSION=24.12.0
ENV NVM_DIR=/root/.nvm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash && \
    . "$NVM_DIR/nvm.sh" && \
    nvm install ${NODE_VERSION} && \
    nvm alias default v${NODE_VERSION} && \
    ln -s "$NVM_DIR/versions/node/v${NODE_VERSION}/bin/node" /usr/local/bin/node && \
    ln -s "$NVM_DIR/versions/node/v${NODE_VERSION}/bin/npm" /usr/local/bin/npm

# Verify Node.js and npm installation
RUN node --version && npm --version

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

# Install Node.js dependencies
RUN npm install && npm run dev


# Install Symfony-cli
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


EXPOSE 8000

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["symfony", "server:start", "--no-interaction", "--allow-all-ip", "--no-tls"]