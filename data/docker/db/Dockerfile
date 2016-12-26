FROM postgres:9.6

MAINTAINER Alexander Harchenko <morontt@gmail.com>

ARG HOST_UID=1000

VOLUME ["/var/lib/postgresql/data"]

RUN usermod -u ${HOST_UID} postgres
RUN groupmod -g ${HOST_UID} postgres

EXPOSE 5432
