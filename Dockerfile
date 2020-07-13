FROM php:7.2-apache

RUN apt-get update \
	# 相关依赖必须手动安装
	&& apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev \
	# memcached 的相关依赖
	&& apt-get install -y libmemcached-dev zlib1g-dev \
	# 安装扩展
	&& docker-php-ext-install -j$(nproc) iconv \
	# 如果安装的扩展需要自定义配置时
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    # pecl 安卓mcrypt php从7.2开始不再在源码里支持mcrypt扩展而转到pecl方式支持
	&& pecl install mcrypt-1.0.1 \
	&& docker-php-ext-enable mcrypt \
    # 其他扩展
    && docker-php-ext-install mysqli \
	&& docker-php-ext-install pdo_mysql \
    # pecl安装php的memcached扩展
    && pecl install memcached \
    # 启用memcached 扩展
    && docker-php-ext-enable memcached \
    # pecl 安装php的redis扩展
    && pecl install redis \
    # 启用redis扩展
    && docker-php-ext-enable redis

COPY . /var/www/html/

RUN chmod -R 755 /var/www/html/cache
RUN chmod -R 755 /var/www/html/data
RUN chmod -R 755 /var/www/html/tslogs
RUN chmod -R 755 /var/www/html/upgrade
RUN chmod -R 755 /var/www/html/uploadfile

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_SERVERADMIN admin@localhost
ENV APACHE_SERVERNAME localhost
ENV APACHE_SERVERALIAS docker.localhost
ENV APACHE_DOCUMENTROOT /var/www

WORKDIR /var/www/html
ENTRYPOINT apache2 -D FOREGROUND