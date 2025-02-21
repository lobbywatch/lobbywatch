# 39 because of https://jira.mariadb.org/browse/CONPY-293
FROM fedora:39

ENV TZ=Europe/Zurich

RUN dnf install -y nano python3.12 python3.12-devel java-17-openjdk mariadb-connector-c-devel gcc php composer php-mysqlnd git rsync mariadb qpdf jq ImageMagick

WORKDIR /opt/lobbywatch

ADD . .

RUN touch public_html/settings/maintenance_mode.php

WORKDIR web_scrapers
RUN pip install -r requirements.txt

WORKDIR /opt/lobbywatch

ENTRYPOINT ["./run_update_ws_parlament.sh"]

CMD ["-h"]
