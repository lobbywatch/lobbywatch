# 39 because of https://jira.mariadb.org/browse/CONPY-293
FROM fedora:39

RUN dnf install -y python3.12 python3.12-devel java-17-openjdk mariadb-connector-c-devel gcc php composer php-mysqlnd git rsync mariadb

WORKDIR /opt/lobbywatch

ADD . .

RUN cd web_scrapers && python3.12 -m venv .venv && source .venv/bin/activate && pip install -r requirements.txt
