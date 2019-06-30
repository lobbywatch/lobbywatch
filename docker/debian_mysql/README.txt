Docker debian-mysql-server-5.7
==============================

Dockerfile for creating debian-mysql-server-5.7.

It is based on Debian Sid (unstable) and containts MySQL 5.7.

It builds on architectures amd64, armhf (armv7), and armel (armv6) (Raspian).

Rationale: Current MySQL 5.7 docker images do not support Raspberry Pi (armhf architecture of Raspian).

The Dockerfile is adpated from https://github.com/mysql/mysql-docker/blob/mysql-server/5.7/Dockerfile

Usage see https://hub.docker.com/r/mysql/mysql-server/

Public respository: https://hub.docker.com/r/ibex/debian-mysql-server-5.7
