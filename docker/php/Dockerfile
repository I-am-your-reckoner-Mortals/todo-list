#Basic php & dependecies
FROM php:7.4-fpm
RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		git \
		npm \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
    && pecl install xdebug-2.8.1

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
WORKDIR /var/www/project

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

#Install composer global
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
        php composer-setup.php --quiet && \
        rm composer-setup.php && \
        mv composer.phar /usr/local/bin/composer && \
        rm -rf /var/lib/{apt,dpkg,cache,log}/
