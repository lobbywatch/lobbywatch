# Copyright (c) 2017, Oracle and/or its affiliates. All rights reserved.
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; version 2 of the License.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA

# Copied from https://github.com/mysql/mysql-docker/tree/mysql-server/5.7

# Build image cmd
# docker build -t debian-mysql-server-5.7 .

# FROM oraclelinux:7-slim
FROM debian:sid

# add our user and group first to make sure their IDs get assigned consistently, regardless of whatever dependencies get added
RUN groupadd -r mysql && useradd -r -g mysql mysql

RUN printf "\nalias ll='ls -l'\nalias l='ls -lA'\n" >> /root/.bashrc

COPY inputrc.txt /etc/inputrc

RUN apt-get update \
  # Debug utils
  && DEBIAN_FRONTEND=noninteractive apt-get install -y apt-utils less nano procps \
  && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends mysql-server-5.7 \
  # Clean cache
  && rm -rf /var/lib/apt/lists/*
RUN rm -rf /var/lib/mysql && mkdir -p /var/lib/mysql /var/run/mysqld \
    && chown -R mysql:mysql /var/lib/mysql /var/run/mysqld \
    # ensure that /var/run/mysqld (used for socket and lock files) is writable regardless of the UID our mysqld instance ends up having at runtime
    && chmod 777 /var/run/mysqld \
    # don't reverse lookup hostnames, they are usually another container
    && printf '[mysqld]\nskip-host-cache\nskip-name-resolve\n' > /etc/mysql/conf.d/docker.cnf

RUN mkdir /docker-entrypoint-initdb.d

VOLUME /var/lib/mysql

COPY docker-entrypoint.sh /entrypoint.sh
COPY healthcheck.sh /healthcheck.sh
ENTRYPOINT ["/entrypoint.sh"]
HEALTHCHECK CMD /healthcheck.sh
EXPOSE 3306 33060
CMD ["mysqld"]
