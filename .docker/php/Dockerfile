FROM php:8.1-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -qq && \
    apt-get dist-upgrade -qqy && \
    apt-get install git -qqy