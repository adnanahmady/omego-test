FROM composer:2.6.6 as composer266
FROM adnanahmady/php:8.2-fpm-alpine

RUN apk add --no-cache --update --force icu-dev git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN echo 'short_open_tag=off' > /usr/local/etc/php/conf.d/docker-php-set-short_open_tag.ini \
    && echo 'post_max_size=400M' > /usr/local/etc/php/conf.d/docker-php-set-post_max_size.ini

COPY --from=composer266 /usr/bin/composer /usr/bin/composer

USER docker

RUN wget https://get.symfony.com/cli/installer -O - | bash
ENV PATH "/home/docker/.symfony5/bin:$PATH"

RUN echo 'alias sym="php bin/console"' >> $HOME/.bashrc \
    && echo 'function make() { php bin/console make:$@; }' >> $HOME/.bashrc \
    && echo 'alias gs="git status"' >> $HOME/.bashrc \
    && echo 'alias gd="git diff"' >> $HOME/.bashrc \
    && echo 'alias g="git"' >> $HOME/.bashrc \
    && echo 'alias ga="git add"' >> $HOME/.bashrc \
    && echo 'alias gl="git log"' >> $HOME/.bashrc \
    && echo 't() { composer test -- $(echo $1 | sed "s/.*tests/tests/"); }' >> $HOME/.bashrc \
    && echo 'alias tf="composer test -- --filter"' >> $HOME/.bashrc

USER root
