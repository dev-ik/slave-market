FROM centos:7

RUN yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN mkdir /run/php-fpm

RUN yum install --enablerepo=remi-php73 -y \
    php \
    php-fpm \
    php-xdebug \

RUN yum install -y git
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer



WORKDIR /slave.market
EXPOSE 9000
CMD php-fpm -F -R
