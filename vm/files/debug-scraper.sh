#!/bin/bash

podman run --rm -it \
--mount=type=bind,source="${SSH_AUTH_SOCK}",target="${SSH_AUTH_SOCK}" \
--mount=type=bind,source=/home/almalinux/scraper/settings.php,target=/opt/lobbywatch/public_html/settings/settings.php,ro \
--mount=type=bind,source=/home/almalinux/scraper/sql,target=/opt/lobbywatch/sql \
--mount=type=bind,source=/home/almalinux/scraper/prod_bak,target=/opt/lobbywatch/prod_bak \
--mount=type=bind,source=/home/almalinux/scraper/archive,target=/opt/lobbywatch/web_scrapers/archive \
--mount=type=bind,source=/home/almalinux/.ssh/known_hosts,target=/root/.ssh/known_hosts,ro \
--mount=type=bind,source=/home/almalinux/scraper/my.cnf,target=/root/.my.cnf,ro \
--network systemd-scraper \
--entrypoint sh \
--privileged \
--env=SSH_AUTH_SOCK \
--env=LW_DB_HOST=mariadb \
localhost/lobbywatch/scraper:production
