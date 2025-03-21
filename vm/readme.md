# Lobbywatch VM Setup

## Initial setup

1. (Your machine) Install some needed packages and prepare the scraper container and scheduling: `ansible-playbook -i inventory.yml setup.yml`
1. (On VM) Put SSH key into `.ssh`
1. (On VM) Add SSH key to agent (see below)
1. (On VM) Place copy of `public_html/settings/example.settings.php` in `~/scraper/settings.php`, change users and passwords accordingly. 
2. Initial DB import, see MariaDB chapter below

## Access

If your SSH public key is allowed, you can connect to the VM via `ssh almalinux@84.234.16.49`

## Scraper

The scraper is made up of several systemd services, which run as the `almalinux` user. They are created using quadlets and systemd units (see chapter "Resources"). They can be inspected with

```shell
systemctl --user status [unit-name]
```

We define the following units

| unit name | filename in repository | purpose |
|-|-|-|
| scraper.timer | scraper.timer  | triggers scraping |
| scraper.service | scraper.container | the service triggered by the timer |
| scraper-build.service | scraper.build | builds the container image (`Containerfile` in repository root) |
| scraper-network.service | scraper.network | creates a container network shared by the scraper and mariadb |
| mariadb.service | mariadb.service | local mariadb | 
| mariadb-volume.service | mariadb.volume | podman volume for local mariadb | 
| ssh-agent.service | ssh-agent.service | ssh-agent service, for access to cyon host | 

You can get their status using

```shell
systemctl --user list-units --all '*scraper*'
```
### Force rebuilding scraper container image

Currently the scraper image must be rebuilt manually whenever changes are made to the repository.

```shell
systemctl --user restart scraper-build.service
```

You can look the build process with `journalctl --user-unit=scraper-build`.

### Scraper shell

```shell
sh scraper/debug-scraper.sh
# shell opens in container, same image as scraper.service uses

# run scraping script
./run_update_ws_parlament.sh -h

# or connect to local mariadb
mariadb -h mariadb -u root -p
```

### Troubleshooting

1. Check output of `journalctl --user-unit=scraper`
2. Check files in `/home/almalinux/scraper/sql`
3. [Open a scraper shell](#scraper-shell) and re-run the scraping process to see errors happening live

## ssh-agent

The scraper containers need a running SSH agent in order to be able to connect to the cyon host. To find out whether it is up and running, run 

```shell
ssh-add -l
```
It should list a single `ED25519` key.

If instead you get `Error connecting to agent: No such file or directory` or just no output at all, then check the SSH agent

### Check status

```shell
systemctl --user status ssh-agent
```

### Restart agent

```shell
systemctl --user restart ssh-agent
```

### Add SSH key to agent

```shell
ssh-add
# you will be asked for a passphrase, which is stored in the IT keepass file 
```

## MariaDB

### Initial DB import

The init script mounted into the mariadb only creates a few basic items. For the import to work you have to import a full dump from the cyon DB. To do this, [open a scraper shell](#scraper-shell) and run

```shell
./run_update_ws_parlament.sh -f
./run_update_ws_parlament.sh -f -i -l=lobbywatch
```

Now both `lobbywatch` and `lobbywatchtest` in the local mariadb contain a recent dump of the cyon database. 

## Resources

- https://www.freedesktop.org/software/systemd/man/latest/systemd.timer.html
- https://docs.podman.io/en/latest/markdown/podman-systemd.unit.5.html
- https://docs.ansible.com/ansible/latest/getting_started/index.html
- https://docs.ansible.com/ansible/latest/collections/ansible/builtin/index.html