# Local dev setup

## Requirements

- Either docker or podman is required. If you use podman replace `docker` with `podman`.
- SSH access to cyon is required

## Setup

```sh
cat <<EOF > .my.cnf
[client]
password=changeme
EOF

cp ../public_html/settings/example.settings.php settings.php # change user to 'root' and password from .my.cnf

docker compose up -d
docker compose run --entrypoint sh scraper 
./run_update_ws_parlament.sh -A -f -i -L # import latest PROD dump to lobbywatch db, say NO at the end
./run_update_ws_parlament.sh -A -f -i -l # import latest PROD dump to lobbywatchtest db, say NO at the end
```

## Run the scraper on your machine

```sh
docker compose run scraper  # this should show ./run_update_ws_parlament.sh's help text 
docker compose run --build scraper  # if the image should be rebuilt before starting the container 
```

