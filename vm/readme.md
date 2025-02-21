# Lobbywatch VM Setup

## Setup

1. Install some needed packages and prepare the scraper container and scheduling: `ansible-playbook -i inventory.yml setup.yml`
3. Put SSH key into `.ssh`
4. Add SSH key to agent (see below)

## Scrapers

The scrapers are made up of several systemd services, which run as the `almalinux` user. They are created using quadlets and systemd units (see chapter "Resources"). They can be inspected with

```shell
systemctl --user status [unit-name]
```

We define the following units

| unit name | filename in repository | purpose |
|-|-|-|
| scraper.timer | scraper.timer  | triggers scraping |
| scraper.service | scraper.container | the service triggered by the timer |
| scraper-build.service | scraper.build | builds the container image (`Containerfile` in repository root) |

You can get their status using

```shell
systemctl --user list-units --all '*scraper*'
```
### Force rebuilding scraper container image

```shell
systemctl --user restart scraper-build.service
```

## ssh-agent

The scraper containers need a running SSH agent in order to be able to connect to the cyon host. To find out whether it is up and running, do

```shell
ssh-add -l
# expected output:
# 256 SHA256:ocmZRMrM/R5jUazQPUEx8REUsdPA6iqC9O0JpxQ11TQ almalinux@ov-65b836.infomaniak.ch (ED25519)
```

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

## Resources

- https://www.freedesktop.org/software/systemd/man/latest/systemd.timer.html
- https://docs.podman.io/en/latest/markdown/podman-systemd.unit.5.html
- https://docs.ansible.com/ansible/latest/getting_started/index.html
- https://docs.ansible.com/ansible/latest/collections/ansible/builtin/index.html