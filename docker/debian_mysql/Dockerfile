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

RUN echo "alias ll='ls -l'" >> /root/.bashrc \
  && echo "alias l='ls -lA'" >> /root/.bashrc

COPY inputrc.txt /etc/inputrc

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y nano less procps apt-utils
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server-5.7 || dpkg --configure -a
RUN DEBIAN_FRONTEND=noninteractive apt-get clean -y
RUN mkdir -p /var/run/mysqld
RUN chown mysql:mysql /var/run/mysqld

RUN mkdir /docker-entrypoint-initdb.d

VOLUME /var/lib/mysql

COPY docker-entrypoint.sh /entrypoint.sh
COPY healthcheck.sh /healthcheck.sh
ENTRYPOINT ["/entrypoint.sh"]
HEALTHCHECK CMD /healthcheck.sh
EXPOSE 3306 33060
CMD ["mysqld"]
