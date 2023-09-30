FROM adminer:latest

USER root

COPY ./tools/docker/adminer/login-password-less.php /var/www/html/plugins-enabled/login-password-less.php

RUN mkdir -p /var/www/html/var/db