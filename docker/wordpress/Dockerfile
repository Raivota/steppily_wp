FROM wordpress:php8.2-apache

RUN a2enmod rewrite headers remoteip expires \
    && { \
        echo 'RemoteIPHeader X-Forwarded-For'; \
        echo 'RemoteIPInternalProxy 10.0.0.0/8'; \
        echo 'RemoteIPInternalProxy 172.16.0.0/12'; \
        echo 'RemoteIPInternalProxy 192.168.0.0/16'; \
      } > /etc/apache2/conf-available/remoteip.conf \
    && a2enconf remoteip

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY wordpress-root/ /usr/src/wordpress/
COPY docker/wordpress/wp-config-docker-extra.php /usr/src/wordpress/wp-config-docker-extra.php
COPY docker/wordpress/docker-entrypoint-wrapper.sh /usr/local/bin/docker-entrypoint-wrapper.sh
RUN rm -f /usr/src/wordpress/.gitkeep \
    && chmod +x /usr/local/bin/docker-entrypoint-wrapper.sh
ENTRYPOINT ["docker-entrypoint-wrapper.sh"]
CMD ["apache2-foreground"]
